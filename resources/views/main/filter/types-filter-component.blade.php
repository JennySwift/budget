<script id="types-filter-template" type="x-template">

    <div v-slide="showContent" class="section">

        <h4 v-on:click="showContent = !showContent" class="center">types</h4>

        <div class="types transition content">

            <div>
                <input type="checkbox">
                <label for="">all</label>
            </div>

            <div>
                <input type="checkbox">
                <label for="">none</label>
            </div>

            <div
                v-show="filterTab === 'show'"
                v-if="filter.types"
                v-for="type in types"
            >
                <input
                        checklist-model="filter.types.in"
                        checklist-value="type"
                        checklist-change="runFilter()"
                        :disabled="filter.types.out.indexOf(type) !== -1"
                        type="checkbox">
                <label
                        v-bind:class="{'disabled': filter.types.out.indexOf(type) !== -1}"
                        for="">
                    @{{ type }}
                </label>
            </div>

            <div
                v-show="filterTab === 'hide'"
                v-if="filter.types"
                v-for="type in types"
            >
                <input
                        checklist-model="filter.types.out"
                        checklist-value="type"
                        checklist-change="runFilter()"
                        :disabled="filter.types.in.indexOf(type) !== -1"
                        type="checkbox">
                <label
                        v-bind:class="{'disabled': filter.types.in.indexOf(type) !== -1}"
                        for="">
                    @{{ type }}
                </label>
            </div>

        </div>

    </div>

</script>