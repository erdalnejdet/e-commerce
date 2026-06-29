<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class CloudinaryService
{
    public function isConfigured(): bool
    {
        return filled(config('cloudinary.cloud_name'))
            && filled(config('cloudinary.api_key'))
            && filled(config('cloudinary.api_secret'));
    }

    public function upload(UploadedFile $file, ?string $folder = null): string
    {
        if (! $this->isConfigured()) {
            throw new RuntimeException(
                'Cloudinary yapılandırması eksik. .env dosyasına CLOUDINARY_CLOUD_NAME, CLOUDINARY_API_KEY ve CLOUDINARY_API_SECRET ekleyin.'
            );
        }

        return $this->uploadToCloudinary($file, $folder ?? config('cloudinary.folder'));
    }

    public function delete(?string $url): void
    {
        if (! $url) {
            return;
        }

        if ($this->isCloudinaryUrl($url)) {
            $this->deleteFromCloudinary($url);

            return;
        }

        if (str_contains($url, '/storage/')) {
            $path = ltrim(str_replace('/storage/', '', parse_url($url, PHP_URL_PATH) ?? ''), '/');
            Storage::disk('public')->delete($path);
        }
    }

    private function uploadToCloudinary(UploadedFile $file, string $folder): string
    {
        $cloudName = config('cloudinary.cloud_name');
        $apiKey = config('cloudinary.api_key');
        $apiSecret = config('cloudinary.api_secret');
        $timestamp = time();

        $params = [
            'folder' => $folder,
            'timestamp' => $timestamp,
        ];

        ksort($params);

        $signaturePayload = collect($params)
            ->map(fn ($value, $key) => "{$key}={$value}")
            ->implode('&');

        $response = Http::asMultipart()->post(
            "https://api.cloudinary.com/v1_1/{$cloudName}/image/upload",
            [
                'file' => fopen($file->getRealPath(), 'r'),
                'api_key' => $apiKey,
                'timestamp' => $timestamp,
                'folder' => $folder,
                'signature' => sha1($signaturePayload.$apiSecret),
            ]
        );

        if (! $response->successful()) {
            throw new RuntimeException('Cloudinary yükleme hatası: '.$response->body());
        }

        return $response->json('secure_url');
    }

    private function deleteFromCloudinary(string $url): void
    {
        $publicId = $this->extractPublicId($url);

        if (! $publicId) {
            return;
        }

        $cloudName = config('cloudinary.cloud_name');
        $apiKey = config('cloudinary.api_key');
        $apiSecret = config('cloudinary.api_secret');
        $timestamp = time();

        $params = [
            'public_id' => $publicId,
            'timestamp' => $timestamp,
        ];

        ksort($params);

        $signaturePayload = collect($params)
            ->map(fn ($value, $key) => "{$key}={$value}")
            ->implode('&');

        Http::asForm()->post(
            "https://api.cloudinary.com/v1_1/{$cloudName}/image/destroy",
            [
                'public_id' => $publicId,
                'api_key' => $apiKey,
                'timestamp' => $timestamp,
                'signature' => sha1($signaturePayload.$apiSecret),
            ]
        );
    }

    private function isCloudinaryUrl(string $url): bool
    {
        return str_contains($url, 'res.cloudinary.com');
    }

    private function extractPublicId(string $url): ?string
    {
        $path = parse_url($url, PHP_URL_PATH);

        if (! $path || ! preg_match('#/upload/(?:v\d+/)?(.+)$#', $path, $matches)) {
            return null;
        }

        return preg_replace('/\.[^.]+$/', '', $matches[1]);
    }
}
