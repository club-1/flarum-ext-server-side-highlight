REVISION = 9-18-stable
REPO = https://github.com/highlightjs/cdn-release

update-assets: css/github.min.css

css/%.min.css: .FORCE
	wget -q $(REPO)/raw/$(REVISION)/build/styles/$*.min.css -O $@

.FORCE:;
.PHONY: .FORCE
