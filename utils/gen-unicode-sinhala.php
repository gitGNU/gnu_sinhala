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
    private $iv = array( "අ", "ආ", "ඇ", "ඈ", "ඉ", "ඊ", "උ", "ඌ", "ඍ", "ඎ", "ඏ", "ඐ", "එ", "ඒ", "ඓ", "ඔ", "ඕ", "ඖ" );

    private $sv = array ( "ං", "ඃ" );

    // Consonants
    private $con = array( "ක", "ඛ", "ග", "ඝ", "ඞ", "ඟ", "ච", "ඡ", "ජ", "ඣ", "ඥ", "ඤ", "ඦ", "ට", "ඨ", "ඩ", "ඪ", "ණ", "ඬ", "ත", "ථ", "ද", "ධ", "න", "ඳ", "ප", "ඵ", "බ", "භ", "ම", "ඹ", "ය", "ර", "ල", "ව", "ශ", "ෂ", "ස", "හ", "ළ", "ෆ" );

    // Dependent vowels
    private $dv = array ( "ා", "ැ", "ෑ", "ි", "ී", "ු", "ූ", "ෘ", "ෲ", "ෟ", "ෳ", "ෙ", "ේ", "ෛ", "ො", "ෝ", "ෞ", "්" );

    private $rakyan = array ( "්‍ර", "්‍ය" );
    private $rep = "ර්‍";

    // Conjuncts
    private $conj = array ( "ක්‍ෂ", "ක්‍ව", "ත්‍ථ", "ත්‍ව", "න්‍ථ", "න්‍ද", "න්‍ධ", "න්‍ව", "ද්‍ව", "ද්‍ධ", "ට්‍ඨ" );

    // Punctuation
    private $punct = '෴';

    public function gen_collation()
    {
        $out = array_merge($this->iv, $this->sv);
        foreach ($this->con as $con)
        {
            $out[] = $con;
            foreach ($this->dv as $dv)
            {
                $out[] = "{$con}{$dv}";
            }
        }
        return $out;
    }

    public function gen_glyphs()
    {
        $out = $this->iv;
        foreach (array_merge($this->con, $this->conj) as $base)
        {
            $out[] = $base;
            foreach (array_merge($this->sv, $this->dv, $this->rakyan) as $dia)
            {
                $out[] = "{$base}{$dia}";
            }
            $out[] = "{$this->rep}{$base}";
        }
        $out[] = $this->punct;
        return $out;
    }

    public function gen_letters()
    {
        return array_merge($this->iv, $this->sv, $this->con, $this->dv, $this->rakyan, array($this->rep));
    }

    public function gen_syllables()
    {
        $out = $this->iv;
        foreach ($this->con as $con)
        {
            $out[] = $con;
            foreach (array_merge($this->sv, $this->dv) as $dia)
            {
                $out[] = "{$con}{$dia}";
            }
        }
        return $out;
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
    $chars = $s->gen_collation();
}
elseif ($argv[0] == 'glyphs')
{
    $chars = $s->gen_glyphs();
}
elseif ($argv[0] == 'letters')
{
    $chars = $s->gen_letters();
}
elseif ($argv[0] == 'syllables')
{
    $chars = $s->gen_syllables();
}
else
{
    echo "$PROGNAME: unknown argument: {$argv[0]}\n";
    usage();
}

if (isset($opts['r']))
{
    shuffle($chars);
}
if (isset($opts['h']))
{
    $chars = array_map("bin2hex", $chars);
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

echo implode("$sep", $chars) . "\n";

exit(0);

// vim: ts=4 sw=4 sts=4 et:
