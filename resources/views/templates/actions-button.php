
<div class="my-btn-dropdown">
    <button ng-click="show.actions = !show.actions" class="my-btn">Actions</button>
    <ul ng-show="show.actions" class="list-group bg-blue">

        <li class="list-group-item left">
            <span>Date format</span>
            dd/mm/yy<input ng-model="preferences.date_format" type="radio" value="dd/mm/yy">
            dd/mm/yyyy<input ng-model="preferences.date_format" type="radio" value="dd/mm/yyyy">
            
        </li>
        <li class="list-group-item left">Mass Delete</li>
        <li class="list-group-item left">Mass Edit</li>

    </ul>
</div>
