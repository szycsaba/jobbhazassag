<?php

namespace App\Support;

final class MarkupTransformer
{
    /** @var array<string, callable(string):string> */
    private array $rules;

    public function __construct()
    {
        $this->rules = [
            'bekezdes' => fn (string $content) => '<p class="pt-2">'.$content.'</p>',
            'bekezdes-kozep' => fn (string $content) => '<p class="h-[55%] text-base font-semibold h-[50%] text-center md:text-left mb-6 md:mb-0">'.$content.'</p>',
            'dolt' => fn (string $content) => '<i>'.$content.'</i>',
            'hasab' => fn (string $content) => '<div class="flex-1 flex flex-col md:flex-col items-center justify-center p-0 md:pr-2">'.$content.'</div>',
            'hasab-cim' => fn (string $content) => '<h5 class="h-[25%] subtitle font-bold text-center mt-2 mb-2 md:mt-0 md:mb-0">'.$content.'</h5>',
            'hasab-ikon-villanykorte' => fn (string $content) => '<div class="h-[20%] flex justify-center"><i id="bulbIcon" class="fa-solid fa-lightbulb" style="color: #1f5145;">'.$content.'</i></div>',
            'hasab-ikon-kez' => fn (string $content) => '<div class="h-[20%] flex justify-center"><i id="handshakeIcon" class="fa-solid fa-handshake" style="color: #1f5145;">'.$content.'</i></div>',
            'hasab-ikon-ora' => fn (string $content) => '<div class="h-[20%] flex justify-center"><i id="clockIcon" class="fa-solid fa-clock" style="color: #1f5145;">'.$content.'</i></div>',
            'zold' => fn (string $content) => '<span class="text-(color:--text-color-green) font-semibold">'.$content.'</span>',
            'vastag' => fn (string $content) => '<div class="font-extrabold">'.$content.'</div>',
        ];
    }

    public function toHtml(string $input): string
    {
        if ($input === '') return '';

        $tags = implode('|', array_map('preg_quote', array_keys($this->rules)));

        $pattern = '#<(' . $tags . ')>(.*?)</\1>#si';

        $prev = null;
        $out  = $input;

        while ($out !== $prev) {
            $prev = $out;
            $out = preg_replace_callback($pattern, function ($m) {
                [$full, $tag, $content] = $m;
                $renderer = $this->rules[strtolower($tag)] ?? null;
                return $renderer ? $renderer($content) : e($full);
            }, $out) ?? $out;
        }

        return $out;
    }
}
