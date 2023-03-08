import "./bootstrap";
import "../css/app.css";
import.meta.glob([
    '../images/**',
    '../fonts/**',
  ]);
import '../css/admin/vendor.min.css'
import '../css/admin/app.min.css'
import "@protonemedia/laravel-splade/dist/style.css";

import { createApp } from "vue/dist/vue.esm-bundler.js";
import { renderSpladeApp, SpladePlugin } from "@protonemedia/laravel-splade";

const el = document.getElementById("testdiv");

createApp({
    render: renderSpladeApp({ el })
})
    .use(SpladePlugin, {
        "max_keep_alive": 10,
        "transform_anchors": true,
        "progress_bar": true
    })
    .mount(el);
