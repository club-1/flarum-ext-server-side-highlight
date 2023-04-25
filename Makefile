REVISION = 11-stable
REPO = https://github.com/highlightjs/cdn-release

update-assets: assets/highlight.min.js assets/github.min.css assets/github-dark.min.css

assets/%.min.js: .FORCE
	wget -q $(REPO)/raw/$(REVISION)/build/$(@F) -O $@

assets/%.min.css: .FORCE
	wget -q $(REPO)/raw/$(REVISION)/build/styles/$*.min.css -O- \
	| sed -E 's/(color:#\w+)/\1 !important/g' \
	> $@

.FORCE:;
.PHONY: .FORCE
