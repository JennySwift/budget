describe('Budget App', function() {
    it('should have a title', function() {

        browser.driver.wait(function () {
            return true;
        }, 5000);

        expect(browser.getTitle()).toEqual('Budget App');
        browser.get('http://budget_app.dev:8000');

    });
});