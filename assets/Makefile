CSSREV = 11-stable
JSREV = 9-18-stable
REPO = https://github.com/highlightjs/cdn-release
THEMES = github github-dark base16/monokai

all: highlight.min.js $(THEMES:=.min.css)

%.min.js: .FORCE
	wget -q $(REPO)/raw/$(JSREV)/build/$(@F) -O $@

%.min.css: .FORCE
	wget -q $(REPO)/raw/$(CSSREV)/build/styles/$*.min.css -O $@

.FORCE:;
.PHONY: .FORCE
