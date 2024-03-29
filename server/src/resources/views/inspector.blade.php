@extends('Gernzy\Server::app')

@section('content')
<h1 class="uk-heading-medium">Dev tools</h1>
<div class="uk-child-width-1-2@s" uk-grid x-data="inspector()" x-init="fetch()">
    <div>
        <div uk-grid>
            <div class="uk-width-auto@m">
                <ul class="uk-tab-left" uk-tab="connect: #component-tab-left; animation: uk-animation-fade">
                    <li><a href="#">First party packages</a></li>
                    <li><a href="#">Third party packages</a></li>
                    <li><a href="#">Payment providers</a></li>
                    <li><a href="#">Actions browser</a></li>
                    <li><a href="#">Publishable Providers</a></li>
                    <li><a href="#">Laravel Logs</a></li>
                </ul>
            </div>
            <div class="uk-width-expand@m">
                <ul id="component-tab-left" class="uk-switcher">
                    <li>
                        <template x-for="(item, index) in providers" :key="index">
                            <div x-bind:class="{ 'uk-badge': item.class }" x-text="item.item"></div>
                        </template>
                    </li>

                    <li>
                        <em>require</em>
                        <template x-for="(item, index) in requirePackages" :key="index">
                            <div x-text="item"></div>
                        </template>
                        <em>require-dev</em>
                        <template x-for="(item, index) in requireDevPackages" :key="index">
                            <div x-text="item"></div>
                        </template>
                    </li>

                    <li>
                        <template x-for="(item, index) in paymentProviders" :key="index">
                            <div>
                                <div class="uk-margin-bottom">
                                    <div class="uk-flex">
                                        <strong class="uk-width-1-3">provider</strong>
                                        <em x-text="item.provider_name" class="uk-width-1-2"></em>
                                    </div>
                                    <div class="uk-flex">
                                        <strong class="uk-width-1-3">info</strong>
                                        <div class="uk-width-1-2">
                                            <div x-text="item.provider_class"></div>
                                            <!-- <div x-text="item.provider_log"></div> -->
                                            <a href="#/" x-on:click="viewLogClick" x-text="item.provider_log"></a>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </template>
                    </li>

                    <li>
                        <template x-for="event in events">
                            <div class="uk-margin-bottom">
                                <div class="uk-flex">
                                    <strong class="uk-width-1-3">event</strong>
                                    <em x-text="event.event" class="uk-width-1-2"></em>
                                </div>

                                <div class="uk-flex">
                                    <strong class="uk-width-1-3">actions</strong>
                                    <div class="uk-width-1-2">
                                        <template x-for="action in event.actions">
                                            <div x-text="action"></div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </li>


                    <li>
                        <template x-for="(item, index) in publishableProviders" :key="index">
                            <div x-bind:class="{ 'uk-badge': item.class }" x-text="item.item"></div>
                        </template>
                    </li>

                    <li>
                        <div class="uk-flex uk-flex-middle uk-margin-bottom">
                            <div class="uk-flex uk-flex-middle uk-width-1-1">

                                <input x-on:change="updateListOfFiles($event)" x-model="dateInput" type="date" id="logdate" name="logdate">

                                <button class="uk-margin-left" uk-icon="settings"></button>
                                <div uk-dropdown="mode: click">
                                    <template x-for="(item, index) in paymentProviders" :key="index">
                                        <div>
                                            <ul class="uk-nav uk-dropdown-nav">
                                                <li class="uk-width-1-2">
                                                    <a x-on:click="filterLogForProviders" :data-provider="item.provider_name" href="#" x-text="item.provider_name"></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </template>
                                </div>

                                <button x-on:click="viewLogResetClick" class="uk-margin-left" uk-icon="refresh"></button>
                            </div>
                        </div>

                        <template x-for="(item, index) in laravel_log" :key="index">
                            <div style="width: 80vw;">
                                <div x-show.transition="item.showLogName" :class="{ 'uk-background-primary': item.fileMatchedKeyWord }" class="uk-card-small uk-card-default uk-card-body uk-margin uk-flex uk-flex-middle uk-card-hover">
                                    <div x-text="item.item" class="uk-width-1-1"></div>
                                    <button :data-log="item.item" class="uk-button uk-button-default uk-button-small uk-margin-left" x-on:click="viewLogClick" x-text="item.button_text">Open</button>
                                </div>

                                <div x-show.transition="item.showLogContentsSpinner" class="uk-flex uk-flex-middle uk-margin-bottom">
                                    <div>Processing log contents...</div>
                                    <div class="uk-margin-left" uk-spinner></div>
                                </div>

                                <!-- Log contents -->
                                <div x-show.transition="item.showLogContents">
                                    <div x-html="logContent"></div>
                                </div>
                            </div>
                        </template>
                    </li>

                </ul>
            </div>
        </div>
    </div>

</div>
@endsection