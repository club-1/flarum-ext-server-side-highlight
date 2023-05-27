CSSREV = 11-stable
JSREV = 9-18-stable
REPO = https://github.com/highlightjs/cdn-release

all: highlight.min.js github.min.css github-dark.min.css

%.min.js: .FORCE
	wget -q $(REPO)/raw/$(JSREV)/build/$(@F) -O $@

%.min.css: .FORCE
	wget -q $(REPO)/raw/$(CSSREV)/build/styles/$*.min.css -O $@

.FORCE:;
.PHONY: .FORCE
