<!-- ==============================edit CSD============================== -->

<div ng-show="show.edit_CSD" class="popup-outer">
    <div class="popup-inner">
        <input ng-model="edit_CSD.CSD" type="text">
        <button ng-click="updateCSD()">done</button>
    </div>
</div>

<!-- ==============================cumulative starting date============================== -->

<div ng-show="show.cumulative_starting_date" class="popup-outer">
    <div class="popup-inner">
        <input id="cumulative-date-input" type="text">
        <button ng-click="updateCumulativeStartingDate()" type="button" id="cumulative-date-button" class="btn btn-default" data-dismiss="modal">Save changes</button>
    </div>
</div>
            
<!-- ==============================mass edit============================== -->

<div ng-show="show.mass_edit" class="popup-outer">
    <div class="popup-inner">
        <div class="row">
            <div class="my-dropdown-container col-sm-4">
                <input type="text" placeholder="add a tag" id="mass-edit-add-tag-input" class="filter-input">
                <div id="mass-edit-add-tag-dropdown" class="my-dropdown"></div>
            </div>
            <div id="mass-edit-add-tag-location" class="tag-location col-sm-4"></div>
            <div class="col-sm-4">
                <button ng-click="massEditTags()" id="mass-edit-add-tag-button" class="btn btn-info btn-xs">add tag(s)</button>
            </div>
        </div>
        <div class="row">
            <div class="my-dropdown-container col-sm-4">
                <input type="text" placeholder="remove a tag" id="mass-edit-remove-tag-input" class="filter-input">
                <div id="mass-edit-remove-tag-dropdown" class="my-dropdown"></div>
            </div>
            <div id="mass-edit-remove-tag-location" class="tag-location col-sm-4"></div>
            <div class="col-sm-4">
                <button ng-click="massEditTags()" id="mass-edit-remove-tag-button" class="btn btn-info btn-xs">remove tag(s)</button>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <input type="text" placeholder="edit description" id="mass-edit-description-input">
            </div>
            <div class="col-sm-4">
                <button ng-click="massEditDescription()" id="mass-edit-description-button" class="btn btn-info btn-xs">edit description</button>
            </div>
        </div>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
</div>
        

<!-- ==============================credits modal============================== -->

<div ng-show="show.credits" class="popup-outer">
    <div id="credits-modal" class="popup-inner">
        <div class="margin-bottom">
            <p><a href="http://bootstraptour.com/">Bootstrap Tour</a></p>
            <p><a href="http://getbootstrap.com/">Bootstrap</a></p>
            <p><a href="http://datejs.com/">Datejs</a></p>
            <p><a href="http://fontawesome.io">Font Awesome by Dave Gandy</a></p>
            <p><a href="http://momentjs.com/">Moment.js</a></p>
            <p><a href="http://craig.is/killing/mice">Mousetrap</a></p>
            <p><a href="http://iamceege.github.io/tooltipster/">Tooltipster</a></p>
        </div>
        <button ng-click="show.credits = false" class="close-modal">close</button>
    </div>
</div>

<!-- ==============================color picker============================== -->

<div ng-show="show.color_picker" class="popup-outer">
    <div id="color-modal" class="popup-inner container">

        <h3 class="center">Choose your colours</h3>
        <div class="wrapper margin-bottom">
            <table class="table table-bordered">
                <tr>
                    <td><label for="income-color-picker">income</label></td>
                    <td><input ng-model="colors.income" id="income-color-picker" class="color-picker" type="color"></td>
                    <td><button ng-click="defaultColor('income', '#017d00')" id="default-income-color-button" class="default-color-button btn btn-info">default</button></td>
                </tr>
                <tr>
                    <td><label for="expense-color-picker">expense</label></td>
                    <td><input ng-model="colors.expense" id="expense-color-picker" class="color-picker" type="color"></td>
                    <td><button ng-click="defaultColor('expense', '#fb5e52')" id="default-expense-color-button" class="default-color-button btn btn-info">default</button></td>
                </tr>
                <tr>
                    <td><label for="transfer-color-picker">transfer</label></td>
                    <td><input ng-model="colors.transfer" id="transfer-color-picker" class="color-picker" type="color"></td>
                    <td><button ng-click="defaultColor('transfer', '#fca700')" id="default-transfer-color-button" class="default-color-button btn btn-info">default</button></td>
                </tr>
            </table>
        </div>

        <button ng-click="updateColors()" class="btn btn-default close-modal">close</button>

    </div>
</div>
       