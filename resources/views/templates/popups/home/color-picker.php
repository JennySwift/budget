<div ng-show="show.color_picker" ng-cloak class="popup-outer">
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