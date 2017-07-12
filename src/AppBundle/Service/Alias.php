<?php

namespace AppBundle\Service;

class Alias
{
    public function makeAlias($name, $id)
    {
        return $this->utf8flat($name . '-' . $id);
    }

    public function decodeAliasToID($alias)
    {
        if (preg_match("@-([0-9]+)$@", $alias, $found))
        {
            return $found[1];
        }
        throw new \Exception("invalid alias structure");
    }
    
    public function utf8flat($s)
    {
        $s = mb_strtolower(normalizer_normalize($s), 'UTF-8');

        $map = [
            'a' => ['@\x{0104}@u','@\x{0105}@u'],
            'c' => ['@\x{0106}@u','@\x{0107}@u'],
            'e' => ['@\x{0118}@u','@\x{0119}@u'],
            'o' => ['@\x{00D3}@u','@\x{00F3}@u'],
            's' => ['@\x{015A}@u','@\x{015B}@u'],
            'l' => ['@\x{0141}@u','@\x{0142}@u'],
            'z' => ['@\x{0179}@u','@\x{017A}@u','@\x{017B}@u','@\x{017C}@u'],
            'n' => ['@\x{0143}@u','@\x{0144}@u'],
        ];

        foreach($map as $letter => $chars)
        {
            $s = preg_replace($chars, $letter, $s);
        }
        $s = preg_replace("@[\-]+@", '-', trim(str_replace(' ', '-', preg_replace("@[^0-9a-z]+@i", ' ', $s)), '-'));
        return $s;
    }
}
