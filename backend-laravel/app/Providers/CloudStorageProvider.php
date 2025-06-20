<?php

namespace App\Providers;

use Exception;
use GuzzleHttp\Client;
use App\Models\Cockpit;
use Illuminate\Support\Str;
use GuzzleHttp\HandlerStack;
use App\Models\CockpitStorage;
use Google\Cloud\Storage\Bucket;
use Illuminate\Support\Facades\Log;
use Google\Cloud\Storage\StorageClient;
use Google\Cloud\Storage\StorageObject;
use Google\Auth\HttpHandler\Guzzle6HttpHandler;

class CloudStorageProvider
{
    /**
     * Undocumented variable
     *
     * @var StorageClient
     */
    public StorageClient $client;

    /**
     * Undocumented variable
     *
     * @var StorageObject
     */
    public StorageObject $clientResponse;

    /**
     * Undocumented variable
     *
     * @var string
     */
    public string $bucketName;

    /**
     * Undocumented variable
     *
     * @var [type]
     */
    public Bucket $bucket;

    /**
     * Undocumented variable
     *
     * @var CockpitStorage|null
     */
    public ?CockpitStorage $storageEntry = null;

    /**
     * Undocumented variable
     *
     * @var Cockpit|null
     */
    private Cockpit $cockpit;

    /**
     * Google Cloud Storage
     * https://github.com/googleapis/google-cloud-php
     * https://cloud.google.com/php/docs/reference/cloud-storage/latest
     *
     * @param Cockpit|null $cockpit
     */
    public function __construct(Cockpit $cockpit)
    {
        $config = [
            'keyFilePath' => config('filesystems.gcs.key_file_path'),
            'projectId' => config('filesystems.gcs.project_id'),
        ];

        // Optional: disable HTTPS verification in local development
        if (config('app.unsafe_http_request')) {
            $guzzleClient = new Client([
                'handler' => HandlerStack::create(),
                'verify' => false,
            ]);
            $config['httpHandler'] = new Guzzle6HttpHandler($guzzleClient);
        }

        // Set Client
        $this->bucketName = config('filesystems.gcs.bucket');
        $this->client = new StorageClient($config);
        $this->bucket = $this->client->bucket($this->bucketName);
        $this->cockpit = $cockpit;
    }

    /**
     * Upload File to Google Cloud Bucket
     *
     * @param object|null $file
     * @param string|null $prefix
     * @return CockpitStorage|null
     */
    public function uploadNewFile(?object $file, ?string $prefix): ?CockpitStorage
    {
        // Check if File is set
        if(!$file) return $this->storageEntry;

        try {

            // Definitions
            $fileName = $this->cockpit->name . '-' . $this->cockpit->id;
            $fileID = $fileName . Str::random(9) . '.' . $file?->extension();
            $bucketLink = $prefix 
                ? $prefix . '/' . $fileID 
                : $fileID;

            $this->clientResponse = $this->bucket->upload(fopen($file->getPathname(), 'r'), [
                'name' => $bucketLink,
                // 'predefinedAcl' => 'publicRead',
            ]);

            // Process clientResponse
            if($info = $this->clientResponse?->info()) {
                $this->storageEntry = CockpitStorage::create([
                    'is_public' => false,
                    'file_id' => $info['name'],
                    'cockpit_id' => $this->cockpit->id,
                    'bucket' => $this->bucketName,
                    'prefix' => $prefix,
                    'name' => $file->getClientOriginalName() ?? $this->cockpit->name . '-Unnamed',
                    'url' => sprintf('https://storage.googleapis.com/%s/%s', $this->bucketName, $info['name']),
                    'url_download' => $info['mediaLink'],
                    'content_type' => $info['contentType'],
                    'size_bytes' => $info['size'],
                ]);
            }
        } 

        // Handle GCS-specific errors
        catch (\Google\Cloud\Core\Exception\ServiceException $e) {
            Log::channel('gcs')->error("GCS Upload Error: " . now(), ['error' => $e->getMessage()]);
        } 
        
        // Code
        catch (\Throwable $e) {
            Log::channel('gcs')->error("Create Processing Error: " . now(), ['error' => $e->getMessage()]);
        }

        return $this->storageEntry;
    }

    /**
     * Remove File from Storage & System
     *
     * @param integer|null $storageID
     * @return void
     */
    public function removeFile(?int $storageID): void
    {
        try {
            if($storageID) {
                $file = CockpitStorage::where([
                    'id' => $storageID,
                    'cockpit_id' => $this->cockpit->id
                ])->first();

                if($file) {
                    try {
                        $object = $this->bucket->object($file->file_id);
                        $object->delete();
                    } catch (Exception $e) {
                        Log::channel('gcs')->error("Remove Bucket File: " . now(), ['error' => $e->getMessage()]);
                    }
                    
                    $file->delete();
                }
            }
        }

        // Handle GCS-specific errors
        catch (\Google\Cloud\Core\Exception\ServiceException $e) {
            Log::channel('gcs')->error("GCS Delete Error: " . now(), ['error' => $e->getMessage()]);
        } 
        
        // Code
        catch (\Throwable $e) {
            Log::channel('gcs')->error("Remove Processing Error: " . now(), ['error' => $e->getMessage()]);
        }
    }
}

