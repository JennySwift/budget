describe('Budget App', function() {

    beforeEach(function () {
        browser.driver.wait(function () {
            return true;
        }, 5000);

        browser.get('http://budget_app.dev:8000');
    });

    it('should have a title', function() {
        expect(browser.getTitle()).toEqual('Budget App');
    });

    //it('should be able to click the test button', function () {
        //var button = element(by.model('testBtn'));
        //var button = element(by.id('test-btn'));
        //button.click();
    //});
    
    it('should be able to fill in an input', function () {
        //Example from docs but 'until' is undefined and 'driver' was undefined
        //unit I instead used 'browser.driver.'
        //var input = browser.driver.wait(until.elementLocated(By.id('test-input')), 10000);

        var input = browser.driver.wait(function () {
            //Apparently this object has no method 'isPresent.'
            return element(by.id('test-input').isPresent())
        }, 10000);

        //var input = element(by.id('test-input'));
        input.sendKeys('hi there');
    });
});