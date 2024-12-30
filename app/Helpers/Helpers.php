<?php

namespace App\Helpers;

use Symfony\Component\Process\Process;

function sendMessage(string $phoneNumber, string $message, ?string $imagePath = null)
{
    // Path to the Node.js script
    $scriptPath = base_path('node_server\server.js');

    $message = base64_encode($message);
    $command = escapeshellcmd("node {$scriptPath} {$phoneNumber} " . escapeshellarg($message));
    
    if ($imagePath) {
        $command .= ' ' . escapeshellarg($imagePath);
    }

    // dd($command);

    // Execute the command and capture output
    $output = [];
    $returnVar = null;
    exec($command, $output, $returnVar);

    if ($returnVar === 0) {
        // Check if the output contains a success message
        $outputText = implode("\n", $output);
        if (str_contains($outputText, 'Message sent successfully')) {
            return [
                'success' => true,
                'message' => 'Message sent successfully!',
                'output' => $outputText,
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Message not sent. Check output logs.',
                'output' => $outputText,
            ];
        }
    } else {
        return [
            'success' => false,
            'message' => 'Node.js script execution failed.',
            'output' => implode("\n", $output),
        ];
    }
}
