<?php

namespace App\Providers;

/**
 * OpenAI - Client Setup
 * https://platform.openai.com/docs/api-reference/introduction
 * 
 * Repository:
 * https://github.com/openai-php/laravel
 * 
 * Important:
 * 'Https' protocoll disabled, on local environment
 * 
 */

use OpenAI;
use Exception;
use GuzzleHttp\Client;
use OpenAI\Responses\Files\CreateResponse;
use OpenAI\Responses\Files\DeleteResponse;
use OpenAI\Responses\Threads\ThreadResponse;
use OpenAI\Responses\Threads\ThreadDeleteResponse;
use OpenAI\Responses\Threads\Runs\ThreadRunResponse;
use OpenAI\Responses\Threads\Messages\ThreadMessageResponse;

class PaddleProvider
{
    /**
     * Undocumented variable
     *
     * @var OpenAI\Client|null
     */
    public ?OpenAI\Client $client;

    /**
     * Select OpenAI Model
     *
     * @var string
     */
    private $aiModel = 'gpt-4o-mini';

    /**
     * OpenAI Assistant ID
     *
     * @var [type]
     */
    private $assistantID = null;

    /**
     * Setup client
     *
     * @param string $assistantID
     */
    function __construct(?string $assistantID) 
    {
        $this->assistantID = $assistantID;
        $this->client = OpenAI::factory()
            ->withHttpClient(new Client([
                'headers' => [
                    'Authorization' => "Bearer " . env('OPENAI_API_KEY'),
                    'OpenAI-Beta' => 'assistants=v2',
                    // 'Model' => $this->aiModel,   
                ],
                'verify' => !env('CLIENT_ALLOW_HTTP_REQUEST', false),
            ]))->make();
    }

    /**
     * Upload File
     *
     * @param string $content
     * @return CreateResponse
     */
    public function uploadFileAsText(string $content): CreateResponse
    {
        try {
            // Make text file
            $tempFilePath = tempnam(sys_get_temp_dir(), 'upload') . '.txt';
            file_put_contents($tempFilePath, $content);
            
            $response = $this->client->files()->upload([
                'purpose' => 'assistants',
                'file' => fopen($tempFilePath, 'r'),
            ]);

            if (!isset($response['id'])) 
                throw new Exception('Failed to create assistance.');

            unlink($tempFilePath);
        } catch (Exception $e) {
            if($tempFilePath) unlink($tempFilePath);
            throw new Exception($e->getMessage());
        }
        
        return $response;
    }

    /**
     * Step 1: Stream Response
     * Create a new thread (conversation) with OpenAI.
     *
     * @return ThreadResponse
     */
    public function createThread(): ThreadResponse
    {
        $response = $this->client->threads()->create([]);

        if (!isset($response['id'])) {
            throw new Exception('Failed to create thread.');
        }

        return $response;
    }
  
    /**
     * Step 2: Stream Response
     * Add a message to the thread.
     *
     * @param string $threadId
     * @param string $content
     * @return ThreadMessageResponse
     */
    public function addMessage(string $threadId, string $content = ""): ThreadMessageResponse
    {
        $response = $this->client->threads()->messages()->create($threadId, [
            'role' => 'user',
            'content' => $content,
        ]);

        if (!isset($response['id'])) {
            throw new Exception('Failed to add message to thread.');
        }

        return $response;
    }

    /**
     * Step 2: Stream Response
     * Add a file message to the thread.
     *
     * @param string $threadId
     * @param string $content
     * @param string $fileID
     */
    public function addFileMessage(string $threadId, string $content = "", $fileID = ""): ThreadMessageResponse
    {
        $response = $this->client->threads()->messages()->create($threadId, [
            'role' => 'user',
            'content' => $content,
            'attachments' => [
                [
                    'file_id' => $fileID,
                    'tools' => [
                        [
                            'type' => 'file_search',
                        ]
                    ]
                ]
            ]
        ]);

        if (!isset($response['id'])) {
            throw new Exception('Failed to add message to thread.');
        }

        return $response;
    }

    /**
     * Step 3: Stream Response
     * Await assistans response to be fetched
     *
     * @param string $threadId
     * @return array
     */
    public function runAssistant(string $threadId): array
    {
        $assistantResponse = $this->client->threads()->runs()->create(
            threadId: $threadId, 
            parameters: [
                'assistant_id' => $this->assistantID,
            ],
        );

        if (!isset($assistantResponse['id'])) {
            throw new Exception('Failed to run assistant.');
        }

        // Await run completition
        $status = 'in_progress';
        while (!( 
            $status === 'completed' 
            || $status === 'cancelled' 
            || $status === 'expired' 
            || $status === 'failed'
            || $status === 'requires_action'
        )) {
            sleep(1);
            $runResponse = $this->retrieveRunResponse($threadId, $assistantResponse['id']);
            $status = $runResponse['status'] ?? 'failed';
        }

        return [
            'assistantResponse' => $assistantResponse,
            'runResponse' => $runResponse
        ];
    }

    /**
     * Step 3.1: Stream Response
     * Check for the assistant's response.
     *
     * @param [type] $threadId
     * @param [type] $runId
     * @return ThreadRunResponse
     */
    public function retrieveRunResponse($threadId, $runId): ThreadRunResponse
    {
        $response = $this->client->threads()->runs()->retrieve(
            threadId: $threadId,
            runId: $runId,
        );

        if (!isset($response['id'])) {
            throw new \Exception('Failed to retrieve run.');
        }
        
        return $response;
    }

    /**
     * Step 4: Stream Response
     * Get the final response message from the thread.
     *
     * @param [type] $threadId
     * @return array
     */
    public function getResponseMessages($threadId): array
    {
        $response = $this->client->threads()->messages()->list($threadId, [
            'limit' => 10,
        ]);

        if (!isset($response['data'][0]['content'])) {
            throw new \Exception('Failed to fetch assistant response.');
        }

        return $response['data'][0]['content'];
    }

    /**
     * Offboarding: Close Thread
     *
     * @param [type] $threadId
     * @return void
     */
    public function closeThread($threadId): ThreadDeleteResponse
    {
        $response = $this->client->threads()->delete($threadId);
        return $response;
    }

     /**
      * Remove file
      *
      * @param string $fileID
      * @return DeleteResponse
      */
    public function removeFile(string $fileID): DeleteResponse
    {
        $response = $this->client->files()->delete($fileID);
        return $response;
    }

    /**
     * Parse JSON Output
     * 
     * Note:
     * Watch out edge-cases, in case assistant does not give json format.
     *
     * @param string $message
     * @return array
     */
    public function parseAssistantResponse(string $message): array
    {
        try {
            // Parse json
            preg_match('/```json(.*?)```/s', $message, $matches);
            $jsonString = $matches[1] ?? '';
            $decoded = json_decode(trim($jsonString), true);

            // Parse Message
            $cleanedMessage = preg_replace('/```json.*?```/s', '', $message);
            $cleanedMessage = str_replace("\n", '', $cleanedMessage);

            return [
                'result' => $decoded['output'] ?? $decoded ?? null,
                'message' => $cleanedMessage,
                'error' => null
            ];
        } catch (Exception $e) {
            return [
                'result' => null,
                'message' => null,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Testing setup
     *
     * @return string message
     */
    public function testConnection(): string
    {
        $result = $this->client->chat()->create([
            'model' => $this->aiModel,
            'messages' => [
                ['role' => 'user', 'content' => 'Hello!'],
            ],
        ]);
        
        return $result?->choices[0]?->message->content;
    }
}
