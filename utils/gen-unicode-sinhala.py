#!/usr/bin/python
# -*- coding: utf-8 -*-

# gen-unicode-sinhala.py - generate lists of Unicode Sinhala
# Copyright (C) 2009 Harshula Jayasuriya <harshula@gmail.com>
#
# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program.  If not, see <http:#www.gnu.org/licenses/>.

# Last Updated: 2009/02/28

from optparse import OptionParser
from codecs import decode
import string
import random

class sinhala_letters:

    def __init__(self):
        self.buf = [ ]

        # Independent vowels
        self.iv = [ "අ", "ආ", "ඇ", "ඈ", "ඉ", "ඊ", "උ", "ඌ", "ඍ", "ඎ", "ඏ", "ඐ", "එ", "ඒ", "ඓ", "ඔ", "ඕ", "ඖ" ];

        self.sv = [ "ං", "ඃ" ];

        # Consonants
        self.con = ["ක", "ඛ", "ග", "ඝ", "ඞ", "ඟ", "ච", "ඡ", "ජ", "ඣ", "ඥ", "ඤ", "ඦ", "ට", "ඨ", "ඩ", "ඪ", "ණ", "ඬ", "ත", "ථ", "ද", "ධ", "න", "ඳ", "ප", "ඵ", "බ", "භ", "ම", "ඹ", "ය", "ර", "ල", "ව", "ශ", "ෂ", "ස", "හ", "ළ", "ෆ" ];

        # Dependent vowels
        self.dv = [ "ා", "ැ", "ෑ", "ි", "ී", "ු", "ූ", "ෘ", "ෲ", "ෟ", "ෳ", "ෙ", "ේ", "ෛ", "ො", "ෝ", "ෞ", "්" ];

        self.rakyan = [ "්‍ර", "්‍ය" ];
        self.rep = [ "ර්‍" ];

        # Conjuncts
        self.conj = [ "ක්‍ෂ", "ක්‍ව", "ත්‍ථ", "ත්‍ව", "න්‍ථ", "න්‍ද", "න්‍ධ", "න්‍ව", "ද්‍ව", "ද්‍ධ", "ට්‍ඨ" ];

        # Punctuation
        self.punct = [ "෴" ];


    def gen_collation(self):
        self.buf = self.iv + self.sv
        self.buf += [ "%s%s" % (con, dia)
                        for con in self.con
                        for dia in [ "" ] + self.dv ]

    def gen_glyphs(self):
        bases = []
        for con in self.con + self.conj:
            bases += [ "%s%s" % (con, suffix)
                        for suffix in [ "" ] + self.rakyan ]
            bases += [ "%s%s" % (rep, con) for rep in self.rep ]

        self.buf = self.iv
        self.buf += [ "%s%s" % (base, dia)
                        for base in bases
                        for dia in [ "" ] + self.dv + self.sv ]
        self.buf += self.rakyan
        self.buf += self.rep
        self.buf += self.punct

    def gen_letters(self):
        self.buf = self.iv + self.sv + self.con + self.dv

    def gen_syllables(self):
        self.buf = self.iv
        self.buf += [ "%s%s" % (con, dia)
                        for con in self.con
                        for dia in [ "" ] + self.dv + self.sv ]

    # TODO: display hex values
    def generate(self, options):
        str = string.join([ unicode(elm, "utf-8") for elm in self.buf ],
                            options.seperator)
        return str.encode("utf-8")

    def reset(self):
        self.buf = [ ]

    def randomise(self):
        random.shuffle(self.buf)


if __name__ == "__main__":

    parser = OptionParser("usage: %prog [options] collation|glyphs|letters|syllables")
    parser.add_option("-H",
                        "--hex",
                        action="store_true",
                        dest="hex",
                        default=False,
                        help="Output in hex")
    parser.add_option("-r",
                        "--randomise",
                        action="store_true",
                        dest="random",
                        default=False,
                        help="Randomise output")
    parser.add_option("-s",
                        "--separator",
                        action="store",
                        dest="seperator",
                        default="\n",
                        help="character(s) used as a delimiter")
    (opts, args) = parser.parse_args()

    if len(args) != 1:
        parser.error("incorrect number of arguments")

    # Need to convert literal strings to the corresponding ASCII representation.
    special_charmap = { "\\t": "\t", "\\n": "\n" }
    if opts.seperator in special_charmap.keys():
        opts.seperator = special_charmap[opts.seperator]

    x = sinhala_letters()

    if "collation" in args:
        x.gen_collation()
    elif "glyphs" in args:
        x.gen_glyphs()
    elif "letters" in args:
        x.gen_letters()
    elif "syllables" in args:
        x.gen_syllables()
    else:
        parser.error("incorrect argument")

    if opts.random:
        x.randomise()

    print x.generate(opts)

# vim: ts=4 sw=4 sts=4 et:
