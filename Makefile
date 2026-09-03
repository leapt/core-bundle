.DEFAULT_GOAL := help
help: ## Show this help.
	@printf "\n Available commands:\n\n"
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-25s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m## */[33m/'
.PHONY: help

docs-start: ## Start development server for documentation on the first available port (starting at 8000)
	@port=8000; \
	while ss -ltn | awk '{print $$4}' | grep -q ":$$port$$"; do \
		port=$$((port + 1)); \
	done; \
	echo "Starting documentation server on http://localhost:$$port/"; \
	docker run --rm -it -p $$port:$$port -v ${PWD}:/docs squidfunk/mkdocs-material serve --dev-addr=0.0.0.0:$$port
.PHONY: docs-start
