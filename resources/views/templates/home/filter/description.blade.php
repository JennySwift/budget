
<div filter-dropdowns-directive
     ng-mouseleave="hideContent($event)"
     class="section">

    <h4 ng-mouseover="showContent($event)" class="center">description</h4>

    <div class="content">
        <div class="group">
            <input ng-model="filter.description" type="text" placeholder="description">
            <span class="input-group-btn">
                <button ng-click="clearFilterField('description')" class="clear-search-button">clear</button>
            </span>
        </div>
    </div>

</div>




