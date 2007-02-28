# Makefile to generate the document in various formats
# Harshula Jayasuriya <harshula at gmail dot com>
# 2007/02/28

DOC_LYX = sinhala-howto.lyx
DOC_SGML = $(DOC_LYX:.lyx=.sgml)
DOC_HTML = $(DOC_LYX:.lyx=.html)
DOC_PDF = $(DOC_LYX:.lyx=.pdf)
DEST = ../../../web/doc/howto
DSSSL = /usr/share/sgml/docbook/stylesheet/dsssl/ldp/ldp.dsl
LYX_BIN = "$(HOME)/source/lyx/lyx-1.4.3/src/lyx"

default: $(DOC_SGML) $(DOC_HTML)

$(DOC_SGML): $(DOC_LYX)
	$(LYX_BIN) --export docbook $(DOC_LYX)

$(DOC_HTML): $(DOC_SGML)
	docbook2html --nochunks --dsl $(DSSSL)#html $(DOC_SGML)

pdf: $(DOC_SGML)
	docbook2pdf --dsl $(DSSSL)#print $(DOC_SGML)

install: $(DOC_HTML)
	cp -af $(DOC_HTML) $(DEST)/

.PHONY : clean
clean:
	rm -f $(DOC_SGML) $(DOC_HTML) $(DOC_PDF)
