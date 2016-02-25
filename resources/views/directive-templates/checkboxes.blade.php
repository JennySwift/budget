<script type="text/v-template" id="checkboxes-template">

    <div class="checkbox-animate">

        <input
            v-model="model"
            type="checkbox"
            id="[[id]]-checkbox">

        <label for="[[id]]-checkbox">
            <div class="label-icon animated">
                <i class="fa fa-check"></i>
            </div>
        </label>

    </div>

</script>