<?php

namespace Website;

class Markdown
{
    public function __construct(private string $string){

    }

    public function toHtml(){
        $text = htmlspecialchars($this->string, ENT_QUOTES, 'UTF-8');

        // strong
        $text = preg_replace('/__(.+?)__/s', '<strong>$1</strong>', $text);
        $text = preg_replace('/\*\*(.+?)\*\*/s', '<strong>$1</strong>', $text);

        // emphasis
        $text = preg_replace('/_(.+?)_/s', '<em>$1</em>', $text);
        $text = preg_replace('/\*(.+?)\*/s', '<em>$1</em>', $text);

        // convert break line
        // windows to unix
        $text = str_replace("\r\n", "\n", $text);
        //macos to unix
        $text = str_replace("\r", "\n", $text);

        // paragraphs
        $text = '<p>' . str_replace("\n\n", '</p><p>', $text) . '</p>';

        // line break
        $text = str_replace("\n", '<br>', $text);


        $text = preg_replace('/\[([^\]]+)]\(([-a-z0-9._~:\/?#@!$&\'()*+,;=%]+)\)/i',
            '<a href="$2">$1</a>', $text);

        return $text;
    }
}