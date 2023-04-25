CSSREV = 11-stable
JSREV = 9-18-stable
REPO = https://github.com/highlightjs/cdn-release

update-assets: assets/highlight.min.js assets/github.min.css assets/github-dark.min.css

assets/%.min.js: .FORCE
	wget -q $(REPO)/raw/$(JSREV)/build/$(@F) -O $@

assets/%.min.css: .FORCE
	wget -q $(REPO)/raw/$(CSSREV)/build/styles/$*.min.css -O- \
	| sed -E 's/(color:#\w+)/\1 !important/g' \
	> $@

.FORCE:;
.PHONY: .FORCE
