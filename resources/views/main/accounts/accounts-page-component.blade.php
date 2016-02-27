<script id="accounts-page-template" type="x-template">

<div>
    <edit-account></edit-account>

    <div id="accounts">

        <div class="create-new-account">
            <label>Create a new account</label>

            <new-account></new-account>

        </div>

        <table class="">
            <tr v-for="account in accounts | orderBy 'name'">
                <td
                        v-on:click="showEditAccountPopup(account)"
                        class="pointer">
                    @{{ account.name }}
                </td>

                <td>
                    <button
                            v-on:click="deleteAccount(account)"
                            class="btn btn-default btn-danger btn-sm">
                        Delete
                    </button>
                </td>
            </tr>
        </table>
    </div>
</div>

</script>