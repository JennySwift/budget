<div ng-show="show.mass_edit" ng-cloak class="popup-outer">
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