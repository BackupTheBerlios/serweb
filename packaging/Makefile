ver        = $(shell head -n 1 debian/changelog | sed -e 's/.*(//;s/-[0-9]).*//')

name       = serweb
pkg_name   = $(name)-$(ver)

prefix     = 
usr_prefix = $(prefix)/usr

docdir     = $(usr_prefix)/share/doc
libexecdir = $(usr_prefix)/lib
datadir    = $(usr_prefix)/share

DOCDIR     = $(docdir)/$(name)
LIBDIR     = $(libexecdir)/$(name)

CVSREL     = -r rel_0_10_0

# ------------------------------------------------------------------------- #

DOC_SRC    = COPYING CHANGELOG INSTALL README TRANSLATIONS VERSION doc/.
SERWEB_SRC = application_layer config data_layer html lang \
	     modules phplib scripts smarty templates

# ------------------------------------------------------------------------- #

SRC_DOC    = $(addprefix serweb/,$(DOC_SRC))
SRC_SERWEB = $(addprefix serweb/,$(SERWEB_SRC))

# ========================================================================= #

all: build

build: fetch

fetch: fetch.stamp

fetch.stamp:
	cvs -d:pserver:anonymous@cvs.berlios.de:/cvsroot/serweb co $(CVSREL) serweb
	cvs -d:pserver:anonymous@cvs.berlios.de:/cvsroot/serweb co overlib
	mv overlib serweb/html/js/
	touch fetch.stamp

i:
	make install prefix=install

install: build
	mkdir -m 0755 -p $(LIBDIR)
	cp -a $(SRC_SERWEB) $(LIBDIR)
	mkdir -m 0755 -p $(DOCDIR)
	cp -a $(SRC_DOC) $(DOCDIR)

clean:
	find . -name '*~' -type f -exec rm -f \{\} \;

cvsclean:
	find . -depth -name 'CVS' -type d -exec rm -rf \{\} \;
	find . -name '.cvsignore' -type f -exec rm -f \{\} \;

treecleen: clean
	rm -rf install $(pkg_name)
	rm -f $(pkg_name).tar.bz2 debian/files *.deb *.dsc *.tar.gz *.changes

nuke: treecleen
	rm -rf serweb
	rm -f fetch.stamp

tree:
	rm -rf $(pkg_name)
	mkdir  $(pkg_name)
	tar c --exclude=$(pkg_name) . | tar -C $(pkg_name) -x
	cd $(pkg_name) && $(MAKE) treecleen

tarball: tree
	tar -cf $(pkg_name).tar $(pkg_name)
	bzip2 -f9 $(pkg_name).tar
	rm -rf $(pkg_name)

deb: tree
	cd $(pkg_name) && $(MAKE) cvsclean
	cd $(pkg_name) && dpkg-buildpackage -rfakeroot -us -uc
	rm -rf $(pkg_name)

version:
	@echo $(ver)

.PHONY: all build i install clean cvsclean nuke tree tarball deb version
