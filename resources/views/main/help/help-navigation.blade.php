<dropdown
        inline-template
        animate-in-class="flipInX"
        animate-out-class="flipOutX"
        :scroll-to="scrollTo"
        id="help-navigation-container"
        class="dropdown-directive"
>

    <div>

        <button
                v-on:click="toggleDropdown()"
                tabindex="-1"
                class="btn btn-info"
        >
            Navigation
            <span class="caret"></span>
        </button>

        <div class="dropdown-content animated">
            <div id="help-navigation" class="help">
                <ul>
                    <li>
                        <a
                            href="#concept-purpose-goal"
                        >
                            Concept/Purpose/Goal
                        </a>
                    </li>

                    <li>
                        <a
                            {{--href="#tags-link"--}}
                            v-on:click="scrollTo('tags-link')"
                            href="javascript:void(0)"
                            {{--datascroll--}}
                        >
                            Tags
                        </a>
                    </li>

                    <li>
                        <a
                            href="#accounts-link"
                        >

                            A

                            c
                            counts
                        </a>
                    </li>

                    <li>
                        <a
                            href="#transactions-link"
                        >

                            T

                            r
                            ansactions
                        </a>
                    </li>

                    <ul>
                        <li>
                            <a
                                href="#allocating"
                            >

                                A
                                llocating the totals
                            </a>
                        </li>

                        <li>
                            <a
                                href="#reconciling"
                            >

                                R
                                econciling
                            </a>
                        </li>
                    </ul>

                    <li>
                        <a
                            href="#savings"
                        >

                            S
                            avings
                        </a>
                    </li>

                    <li>
                        <a
                            href="#RB"
                        >
                            Remaining Balance
                        </a>
                    </li>

                    <li>
                        <a
                            href="#totals"
                        >
                            Totals
                        </a>
                    </li>

                    <li>
                        <a
                            href="#graphs-help"
                        >
                            Graphs
                        </a>
                    </li>
                </ul>
            </div>
        </div>

    </div>

</dropdown>