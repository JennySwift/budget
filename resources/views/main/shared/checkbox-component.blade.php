<script id="checkbox-template" type="x-template">

<div>
    <input
            v-model="model"
            type="checkbox"
            id="@{{ id }}-checkbox">

    <label for="@{{ id }}-checkbox">
        <div class="label-icon animated">
            <i class="fa fa-check"></i>
        </div>
    </label>
</div>

</script>