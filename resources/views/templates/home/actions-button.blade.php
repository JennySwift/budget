
<div dropdowns-directive animate-in="flipInX" animate-out="flipOutX" class="dropdown-directive">
    <button v-on:click="toggleDropdown()" class="btn btn-info">
        Actions
        <span class="caret"></span>
    </button>

    <div id="actions" class="dropdown-content animated">
        <div>Mass Delete</div>
        <div>Mass Edit</div>
    </div>
</div>