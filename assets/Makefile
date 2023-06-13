CSSREV = 11-stable
JSREV = 9-18-stable
REPO = https://github.com/highlightjs/cdn-release
THEMES = github github-dark base16/monokai

all: highlight.min.js $(THEMES:=.min.css)

%.min.js: .FORCE
	wget -q $(REPO)/raw/$(JSREV)/build/$(@F) -O $@

%.min.css: .FORCE
	wget -q $(REPO)/raw/$(CSSREV)/build/styles/$*.min.css -O $@

styles.min.js: .FORCE
	printf 'let hljsStyles = ' > $@
	curl -s https://api.github.com/repos/highlightjs/cdn-release/git/trees/main?recursive=1 \
	| jq -c '[.tree[].path|select(.|startswith("build/styles/") and endswith(".min.css"))|ltrimstr("build/styles/")|rtrimstr(".min.css")]' \
	>> $@

.FORCE:;
.PHONY: .FORCE
