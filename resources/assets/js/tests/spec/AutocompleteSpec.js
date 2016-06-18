var vm = new Vue(Autocomplete);

describe('autocomplete-component', function () {
    it('works', function () {
        //Check the initial data
        expect(vm.autocompleteOptions).toEqual([]);
        expect(vm.chosenOption).toEqual({
            title: '',
            name: ''
        });
        expect(vm.showDropdown).toBe(false);
        expect(vm.currentIndex).toBe(0);

        //Set the data
        vm.unfilteredAutocompleteOptions = [
            {name: 'kangaroo'},
            {name: 'koala'},
            {name: 'wombat'}
        ];

        //This is what has been typed
        vm.chosenOption.name = 'kan';
        vm.prop = 'name';

        //Call the first function
        vm.respondToFocus();

        //Check stuff after the field has been focused
        expect(vm.showDropdown).toBe(true);
        expect(vm.showDropdown).toBe(true);

        expect(vm.autocompleteOptions).toEqual([
            {name: 'kangaroo'}
        ]);
    });
});