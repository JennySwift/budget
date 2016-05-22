<div>

    <h3 class="center">Choose your colours</h3>

    <div class="colors">

        <div class="color">

            <div class="form-group">
                <label for="income-color-picker">Income</label>

                <div class="input-group">
                    <input
                            v-model="me.preferences.colors.income"
                            type="color"
                            id="income-color-picker"
                            name="income-color-picker"
                            placeholder="income"
                            class="form-control"
                    >

                    <span class="input-group-btn">
                        <button
                                v-on:click="defaultColor('income', '#017d00')"
                                class="default-color-button btn btn-default">
                            Default
                        </button>
                    </span>
                </div>

            </div>

        </div>

        <div class="color">

            <div class="form-group">
                <label for="expense-color-picker">Expense</label>

                <div class="input-group">
                    <input
                            v-model="me.preferences.colors.expense"
                            type="color"
                            id="expense-color-picker"
                            name="expense-color-picker"
                            placeholder="expense"
                            class="form-control"
                    >
                    <span class="input-group-btn">
                        <button
                                v-on:click="defaultColor('expense', '#fb5e52')"
                                class="default-color-button btn btn-default">
                            Default
                        </button>
                    </span>
                </div>


            </div>

        </div>

        <div class="color">

            <div class="form-group">
                <label for="transfer-color-picker">Transfer</label>

                <div class="input-group">
                    <input
                            v-model="me.preferences.colors.transfer"
                            type="color"
                            id="transfer-color-picker"
                            name="transfer-color-picker"
                            placeholder="transfer"
                            class="form-control"
                    >
                    <span class="input-group-btn">
                        <button
                                v-on:click="defaultColor('transfer', '#fca700')"
                                class="default-color-button btn btn-default">
                            Default
                        </button>
                    </span>
                </div>

            </div>

        </div>

    </div>

</div>