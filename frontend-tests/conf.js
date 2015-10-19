exports.config = {
    framework: 'jasmine2',
    seleniumAddress: 'http://localhost:4444/wd/hub',
    specs: ['spec.js'],

    onPrepare: function() {
        browser.driver.get('http://budget_app.dev:8000/auth/login');

        browser.driver.findElement(by.name('email')).sendKeys('cheezyspaghetti@gmail.com');
        browser.driver.findElement(by.name('password')).sendKeys('abcdefg');
        browser.driver.findElement(by.tagName('button')).click();
    }

}