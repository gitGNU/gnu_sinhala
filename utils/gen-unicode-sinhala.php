#!/usr/bin/php
<?php

// gen-unicode-sinhala.php - generate lists of Unicode Sinhala
// Copyright (C) 2007 Harshula Jayasuriya <harshula@gmail.com>
//
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program.  If not, see <http://www.gnu.org/licenses/>.

// Last Updated: 2007/07/12

class sinhala_letters
{
    // Independent vowels
    private static $iv = array( "අ", "ආ", "ඇ", "ඈ", "ඉ", "ඊ", "උ", "ඌ", "ඍ", "ඎ", "ඏ", "ඐ", "එ", "ඒ", "ඓ", "ඔ", "ඕ", "ඖ" );

    private static $sv = array ( "ං", "ඃ" );

    // Consonants
    private static $con = array( "ක", "ඛ", "ග", "ඝ", "ඞ", "ඟ", "ච", "ඡ", "ජ", "ඣ", "ඥ", "ඤ", "ඦ", "ට", "ඨ", "ඩ", "ඪ", "ණ", "ඬ", "ත", "ථ", "ද", "ධ", "න", "ඳ", "ප", "ඵ", "බ", "භ", "ම", "ඹ", "ය", "ර", "ල", "ව", "ශ", "ෂ", "ස", "හ", "ළ", "ෆ" );

    // Dependent vowels
    private static $dv = array ( "ා", "ැ", "ෑ", "ි", "ී", "ු", "ූ", "ෘ", "ෲ", "ෟ", "ෳ", "ෙ", "ේ", "ෛ", "ො", "ෝ", "ෞ", "්" );

    private static $rakyan = array ( "්‍ර", "්‍ය" );
    private static $rep = "ර්‍";

    // Conjuncts
    private static $conj = array ( "ක්‍ෂ", "ක්‍ව", "ත්‍ථ", "ත්‍ව", "න්‍ථ", "න්‍ද", "න්‍ධ", "න්‍ව", "ද්‍ව", "ද්‍ධ", "ට්‍ඨ" );

    // Punctuation
    private static $punct = '෴';

    private $buf = null;

    public function gen_collation()
    {
        $this->buf = array_merge(self::$iv, self::$sv);
        foreach (self::$con as $con)
        {
            $this->buf[] = $con;
            foreach (self::$dv as $dv)
            {
                $this->buf[] = "{$con}{$dv}";
            }
        }
    }

    public function gen_glyphs()
    {
        $this->buf = self::$iv;
        foreach (array_merge(self::$con, self::$conj) as $base)
        {
            $this->buf[] = $base;
            foreach (array_merge(self::$sv, self::$dv, self::$rakyan) as $dia)
            {
                $this->buf[] = "{$base}{$dia}";
            }
            $this->buf[] = self::$rep . $base;
        }
        $this->buf[] = self::$punct;
    }

    public function gen_letters()
    {
        $this->buf = array_merge(self::$iv, self::$sv, self::$con, self::$dv, self::$rakyan, array(self::$rep));
    }

    public function gen_syllables()
    {
        $this->buf = self::$iv;
        foreach (self::$con as $con)
        {
            $this->buf[] = $con;
            foreach (array_merge(self::$sv, self::$dv) as $dia)
            {
                $this->buf[] = "{$con}{$dia}";
            }
        }
    }

    public function generate()
    {
        return $this->buf;
    }

    public function set_hex()
    {
        if ($this->buf === null)
        {
            return false;
        }
        $this->buf = array_map("bin2hex", $this->buf);
        return true;
    }

    public function set_random()
    {
        if ($this->buf === null)
        {
            return false;
        }
        shuffle($this->buf);
        return true;
    }
}

$MAP_ARGS = array('\t' => "\t", '\n' => "\n");
$PROGNAME = basename($argv[0]);
array_shift($argv);

function usage()
{
    global $PROGNAME;

    echo<<<OUT
usage: $PROGNAME [-h|-r|-s separator] ARG
       -h: output in hex
       -r: randomise output
       -s: separator: character(s) used as a delimiter
       ARG:
           collation
           glyphs
           letters
           syllables

OUT;
    exit(1);
}

$opts = getopt("hrs:");
if ($opts === false)
{
    usage();
}
foreach ($opts as $key => $val)
{
    array_shift($argv);
    if (!empty($val))
    {
        array_shift($argv);
    }
}

if (count($argv) != 1)
{
    usage();
}

$s = new sinhala_letters();

if ($argv[0] == 'collation')
{
    $s->gen_collation();
}
elseif ($argv[0] == 'glyphs')
{
    $s->gen_glyphs();
}
elseif ($argv[0] == 'letters')
{
    $s->gen_letters();
}
elseif ($argv[0] == 'syllables')
{
    $s->gen_syllables();
}
else
{
    echo "$PROGNAME: unknown argument: {$argv[0]}\n";
    usage();
}

if (isset($opts['r']))
{
    $s->set_random();
}
if (isset($opts['h']))
{
    $s->set_hex();
}
$sep = "\n";
if (isset($opts['s']))
{
    $sep = $opts['s'];
    foreach ($MAP_ARGS as $key => $val)
    {
        $sep = str_replace($key, $val, $sep);
    }
}

echo implode("$sep", $s->generate()) . "\n";

exit(0);

// vim: ts=4 sw=4 sts=4 et:
