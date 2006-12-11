NAME=serweb
RELEASE=$(shell cat VERSION)
TAR_NAME=$(NAME)-$(RELEASE)

TAR=/bin/tar
TAR_ARGS=

.PHONY: tar
tar:
	$(TAR) -C .. \
		--exclude=CVS* \
		--exclude=.cvsignore \
		--exclude=$(notdir $(CURDIR))/tmp* \
		--exclude=*.gz \
		--exclude=*.bz2 \
		--exclude=*.tar \
		--exclude=*.patch \
		--exclude=.\#* \
		--exclude=*.swp \
		$(TAR_ARGS) \
		-cf - $(notdir $(CURDIR)) | \
		   (mkdir -p tmp/_tar1 tmp/_tar2; cd tmp/_tar1; $(TAR) -xf - ) && \
		   mv tmp/_tar1/$(notdir $(CURDIR)) tmp/_tar2/"$(NAME)-$(RELEASE)" && \
		   (cd tmp/_tar2 && $(TAR) -zcf ../../"$(TAR_NAME)".tar.gz "$(NAME)-$(RELEASE)" ) ; \
		   rm -rf tmp/_tar1 tmp/_tar2; 

.PHONY: clean
clean:
	rm -f *.tar.gz

