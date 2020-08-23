#For Development

BRANCH := $(shell git name-rev --name-only HEAD)

pull:
	@echo ">>> Pull Code on Current branch [$(BRANCH)]"
	git pull origin $(BRANCH) --rebase

push: pull
	@echo ">>> Current branch [$(BRANCH)] Pushing Code"
	git push origin $(BRANCH)

# INSIDE VAGRANT ! Need to run this inside Vagrant
# Test would ignore group w/Ignore doc
# Testdox to display results
test:
	@echo ">>> Test"
	phpunit7 --exclude-group ignore --testdox

# INSIDE VAGRANT ! Need to run this inside Vagrant
# Test w/ Code CoverageReport
# Notice: you need xDebug Enabled

test-report:
	@echo ">>> Test w/ Code Coverage report"
	phpunit7 --coverage-html ./application/tests/phpunit-coverage --exclude-group ignore
