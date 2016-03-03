<script id="edit-transaction-popup-template" type="x-template">

	<div
		v-show="showPopup"
		v-on:click="closePopup($event)"
		class="popup-outer"
	>

		<div id="edit-transaction" class="popup-inner">

			<div class="form-group">
			    <label for="selected-transaction-date">Date</label>
			    <input
			        v-model="selectedTransaction.userDate"
			        type="text"
			        id="selected-transaction-date"
			        name="selected-transaction-date"
			        placeholder="date"
			        class="form-control"
			    >
			</div>

			<div class="form-group">
			    <label for="selected-transaction-description">Description</label>
			    <input
			        v-model="selectedTransaction.description"
			        type="text"
			        id="selected-transaction-description"
			        name="selected-transaction-description"
			        placeholder="description"
			        class="form-control"
			    >
			</div>

			<div class="form-group">
			    <label for="selected-transaction-merchant">Merchant</label>
			    <input
			        v-model="selectedTransaction.merchant"
			        type="text"
			        id="selected-transaction-merchant"
			        name="selected-transaction-merchant"
			        placeholder="merchant"
			        class="form-control"
			    >
			</div>

			<div class="form-group">
			    <label for="selected-transaction-total">Total</label>
			    <input
			        v-model="selectedTransaction.total"
			        type="text"
			        id="selected-transaction-total"
			        name="selected-transaction-total"
			        placeholder="$"
			        class="form-control"
			    >
			</div>

			<div class="form-group">
			    <label for="selected-transaction-type">Type</label>

			    <select
			        v-model="selectedTransaction.type"
			        id="selected-transaction-type"
			        class="form-control"
			    >
			        <option
			            v-for="type in types"
			            v-bind:value="type"
			        >
			            @{{ type }}
			        </option>
			    </select>
			</div>

			<div class="form-group">
			    <label for="selected-transaction-account">Account</label>

			    <select
			        v-model="selectedTransaction.account"
			        id="selected-transaction-account"
			        class="form-control"
			    >
			        <option
			            v-for="account in accounts"
			            v-bind:value="account"
			        >
			            @{{ account.name }}
			        </option>
			    </select>
			</div>

			<div v-show="selectedTransaction.type === 'transfer'" class="col-xs-12">
				<select
						v-model="selectedTransaction.fromAccount"
						id="selected-transaction-from-account"
						class="form-control"
				>
					<option
							v-for="account in accounts"
							v-bind:value="account"
					>
						@{{ account.name }}
					</option>
				</select>
			</div>

			<div v-show="selectedTransaction.type === 'transfer'" class="col-xs-12">
				<select
						v-model="selectedTransaction.toAccount"
						id="selected-transaction-to-account"
						class="form-control"
				>
					<option
							v-for="account in accounts"
							v-bind:value="account"
					>
						@{{ account.name }}
					</option>
				</select>
			</div>

			{{--<label>Duration</label>--}}
			{{--<div>@{{ selectedTransaction.minutes }}</div>--}}
			{{--<input v-model="selectedTransaction.duration"--}}
				   {{--placeholder="duration"--}}
				   {{--type='text'>--}}

			{{--<div>--}}
				{{--<label>Reconciled</label>--}}

				{{--<checkbox--}}
						{{--model="selectedTransaction.reconciled">--}}
				{{--</checkbox>--}}
			{{--</div>--}}

			{{--<tag-autocomplete-directive--}}
					{{--v-if="selectedTransaction.type !== 'transfer'"--}}
					{{--chosenTags="selectedTransaction.budgets"--}}
					{{--dropdown="selectedTransaction.dropdown"--}}
					{{--tags="budgets"--}}
					{{--multipleTags="true">--}}
			{{--</tag-autocomplete-directive>--}}

			<div class="buttons">
				<button v-on:click="showPopup = false" class="btn btn-default">Cancel</button>
				<button v-on:click="deleteTransaction(transaction)" class="btn btn-danger">Delete</button>
				<button v-on:click="updateTransaction()" class="btn btn-success">Save</button>
			</div>

		</div>
	</div>


</script>
