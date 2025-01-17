<?php

declare(strict_types=1);

namespace DJWeb\Framework\Log\Formatters;

use Carbon\Carbon;
use DJWeb\Framework\Log\Contracts\FormatterContract;
use DJWeb\Framework\Log\Message;
use SimpleXMLElement;

final readonly class XmlFormatter implements FormatterContract
{
    /**
     * @param Message $message
     *
     * @return array<string, mixed>
     */
    public function toArray(Message $message): array
    {
        $level = $message->level->name ?? $message->level;
        return [
            'datetime' => Carbon::now()->format('Y-m-d H:i:s'),
            'level' => $level,
            'message' => $message->message,
            'context' => $message->context->all(),
            'metadata' => $message->metadata?->toArray(),
        ];
    }

    public function format(Message $message): string
    {
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><log/>');
        $this->arrayToXml($this->toArray($message), $xml);
        /** @var \DOMDocument $dom */
        $dom = dom_import_simplexml($xml)->ownerDocument;
        $dom->formatOutput = true;
        $data = $dom->saveXML();
        return $data ? $data : '';
    }

    /**
     * @param array<string, mixed> $data
     * @param SimpleXMLElement $xml
     *
     * @return void
     */
    private function arrayToXml(array $data, SimpleXMLElement $xml): void
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $subnode = $xml->addChild($key);
                $this->arrayToXml($value, $subnode);

            } else {
                $xml->addChild($key, htmlspecialchars((string) $value));

            }

        }
    }

}
