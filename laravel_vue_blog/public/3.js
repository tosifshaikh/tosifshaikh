(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[3],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/admin/pages/Blogs.vue?vue&type=script&lang=js&":
/*!*****************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/admin/pages/Blogs.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/regenerator */ "./node_modules/@babel/runtime/regenerator/index.js");
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _components_DeleteModal_vue__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../components/DeleteModal.vue */ "./resources/js/admin/components/DeleteModal.vue");
/* harmony import */ var vuex__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! vuex */ "./node_modules/vuex/dist/vuex.esm.js");
function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); enumerableOnly && (symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; })), keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = null != arguments[i] ? arguments[i] : {}; i % 2 ? ownKeys(Object(source), !0).forEach(function (key) { _defineProperty(target, key, source[key]); }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)) : ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }



function asyncGeneratorStep(gen, resolve, reject, _next, _throw, key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { Promise.resolve(value).then(_next, _throw); } }

function _asyncToGenerator(fn) { return function () { var self = this, args = arguments; return new Promise(function (resolve, reject) { var gen = fn.apply(self, args); function _next(value) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "next", value); } function _throw(err) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "throw", err); } _next(undefined); }); }; }

//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//


/* harmony default export */ __webpack_exports__["default"] = ({
  name: "blogs",
  data: function data() {
    return {
      data: {},
      headers: [{
        name: '#'
      }, {
        name: 'Title'
      }, {
        name: 'Categories'
      }, {
        name: 'Tags'
      }, {
        name: 'Views'
      }, {
        name: 'Actions'
      }],
      blogs: [],
      AddModal: false,
      EditModal: false,
      isAdding: false,
      index: -1,
      showDeleteModal: false,
      deleteItem: {},
      deleteIndex: -1,
      modalLoading: false
    };
  },
  mounted: function mounted() {//this.getdata();
  },
  methods: {
    showDeletingModal: function showDeletingModal(data, index) {
      /*   this.deleteItem = data;
        this.deleteIndex = index;
        this.showDeleteModal = true; */
      var deleteModalObj = {
        showDeleteModal: true,
        deleteURL: 'app/delete_blog',
        data: {
          id: data.id
        },
        deleteIndex: index,
        msg: 'Are you sure you want to delete this blog?',
        successMsg: 'Blog has been deleted successfully!',
        isDeleted: false
      };
      this.$store.commit('setDeletingModalObj', deleteModalObj);
    },
    getdata: function getdata() {
      var _this = this;

      return _asyncToGenerator( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.mark(function _callee() {
        var res;
        return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.wrap(function _callee$(_context) {
          while (1) {
            switch (_context.prev = _context.next) {
              case 0:
                _this.token = window.Laravel.csrfToken;
                _context.next = 3;
                return _this.callApi('get', '/app/blog-data');

              case 3:
                res = _context.sent;

                if (res.status == 200) {
                  _this.blogs = res.data;
                }

              case 5:
              case "end":
                return _context.stop();
            }
          }
        }, _callee);
      }))();
    },
    dataLength: function dataLength(data) {
      return data.length;
    }
  },
  created: function created() {
    this.getdata();
    /*  const res = await this.callApi('get','/app/get_tag');
     if (res.status == 200) {
         this.tags = res.data;
     } */
  },
  components: {
    deleteModal: _components_DeleteModal_vue__WEBPACK_IMPORTED_MODULE_1__["default"]
  },
  computed: _objectSpread({}, Object(vuex__WEBPACK_IMPORTED_MODULE_2__["mapGetters"])(['getdeleteModalObj'])),
  watch: {
    getdeleteModalObj: function getdeleteModalObj(value) {
      // console.log(value,'valuetagout');
      if (value.isDeleted) {
        this.blogs.splice(value.deleteIndex, 1); //console.log(value,'valuetag',value.deleteIndex);
      }
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/admin/pages/Blogs.vue?vue&type=template&id=908d9ad0&":
/*!*********************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/admin/pages/Blogs.vue?vue&type=template&id=908d9ad0& ***!
  \*********************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function () {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", [
    _vm._m(0),
    _vm._v(" "),
    _c("div", { staticClass: "row" }, [
      _c("div", { staticClass: "col-xl-12" }, [
        _c("div", { staticClass: "card" }, [
          _c(
            "div",
            { staticClass: "card-header" },
            [
              _c(
                "Button",
                {
                  on: {
                    click: function ($event) {
                      return _vm.$router.push("/create-blog")
                    },
                  },
                },
                [
                  _c("Icon", { attrs: { type: "md-add" } }),
                  _vm._v(" Create Blog"),
                ],
                1
              ),
            ],
            1
          ),
          _vm._v(" "),
          _c("div", { staticClass: "card-body table-border-style" }, [
            _c(
              "div",
              { staticClass: "table-responsive" },
              [
                _c("table", { staticClass: "table" }, [
                  _c("thead", [
                    _c(
                      "tr",
                      _vm._l(_vm.headers, function (header, indx) {
                        return _c("th", { key: indx }, [
                          _vm._v(_vm._s(header.name)),
                        ])
                      }),
                      0
                    ),
                  ]),
                  _vm._v(" "),
                  _c(
                    "tbody",
                    [
                      _vm._l(_vm.blogs, function (blog, i) {
                        return _c("tr", { key: i }, [
                          _c("td", [_vm._v(_vm._s(i + 1))]),
                          _vm._v(" "),
                          _c("td", [_vm._v(_vm._s(blog.title))]),
                          _vm._v(" "),
                          _c(
                            "td",
                            _vm._l(blog.cat, function (c, j) {
                              return _c(
                                "span",
                                { key: j },
                                [
                                  _c("Tag", { attrs: { type: "border" } }, [
                                    _vm._v(_vm._s(c.category_name)),
                                  ]),
                                ],
                                1
                              )
                            }),
                            0
                          ),
                          _vm._v(" "),
                          _c(
                            "td",
                            _vm._l(blog.tag, function (t, jj) {
                              return _c(
                                "span",
                                { key: jj },
                                [
                                  _c("Tag", { attrs: { type: "border" } }, [
                                    _vm._v(_vm._s(t.tagName)),
                                  ]),
                                ],
                                1
                              )
                            }),
                            0
                          ),
                          _vm._v(" "),
                          _c("td", [_vm._v(_vm._s(blog.views))]),
                          _vm._v(" "),
                          _c(
                            "td",
                            [
                              _c(
                                "Button",
                                { attrs: { type: "info", size: "small" } },
                                [_vm._v("Visit Blog")]
                              ),
                              _vm._v(" "),
                              _vm.isUpdatePermitted
                                ? _c(
                                    "Button",
                                    {
                                      attrs: { type: "info", size: "small" },
                                      on: {
                                        click: function ($event) {
                                          return _vm.showEditModal(blog, i)
                                        },
                                      },
                                    },
                                    [_vm._v("Edit")]
                                  )
                                : _vm._e(),
                              _vm._v(" "),
                              _vm.isDeletePermitted
                                ? _c(
                                    "Button",
                                    {
                                      attrs: { type: "error", size: "small" },
                                      on: {
                                        click: function ($event) {
                                          return _vm.showDeletingModal(blog, i)
                                        },
                                      },
                                    },
                                    [_vm._v("Delete")]
                                  )
                                : _vm._e(),
                            ],
                            1
                          ),
                        ])
                      }),
                      _vm._v(" "),
                      _vm.dataLength(_vm.blogs) <= 0
                        ? _c("tr", [_c("td", [_vm._v("No Data")])])
                        : _vm._e(),
                    ],
                    2
                  ),
                ]),
                _vm._v(" "),
                _c("deleteModal"),
              ],
              1
            ),
          ]),
        ]),
      ]),
    ]),
  ])
}
var staticRenderFns = [
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "page-header" }, [
      _c("div", { staticClass: "page-block" }, [
        _c("div", { staticClass: "row align-items-center" }, [
          _c("div", { staticClass: "col-md-12" }, [
            _c("div", { staticClass: "page-header-title" }, [
              _c("h5", { staticClass: "m-b-10" }, [_vm._v("Blogs")]),
            ]),
            _vm._v(" "),
            _c("ul", { staticClass: "breadcrumb" }, [
              _c("li", { staticClass: "breadcrumb-item" }, [
                _c("a", { attrs: { href: "index.html" } }, [
                  _c("i", { staticClass: "feather icon-home" }),
                ]),
              ]),
              _vm._v(" "),
              _c("li", { staticClass: "breadcrumb-item" }, [
                _c("a", { attrs: { href: "#!" } }, [_vm._v("Dashboard")]),
              ]),
              _vm._v(" "),
              _c("li", { staticClass: "breadcrumb-item" }, [
                _c("a", { attrs: { href: "#!" } }, [_vm._v("Blogs")]),
              ]),
            ]),
          ]),
        ]),
      ]),
    ])
  },
]
render._withStripped = true



/***/ }),

/***/ "./resources/js/admin/pages/Blogs.vue":
/*!********************************************!*\
  !*** ./resources/js/admin/pages/Blogs.vue ***!
  \********************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Blogs_vue_vue_type_template_id_908d9ad0___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Blogs.vue?vue&type=template&id=908d9ad0& */ "./resources/js/admin/pages/Blogs.vue?vue&type=template&id=908d9ad0&");
/* harmony import */ var _Blogs_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Blogs.vue?vue&type=script&lang=js& */ "./resources/js/admin/pages/Blogs.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Blogs_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Blogs_vue_vue_type_template_id_908d9ad0___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Blogs_vue_vue_type_template_id_908d9ad0___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/admin/pages/Blogs.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/admin/pages/Blogs.vue?vue&type=script&lang=js&":
/*!*********************************************************************!*\
  !*** ./resources/js/admin/pages/Blogs.vue?vue&type=script&lang=js& ***!
  \*********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Blogs_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./Blogs.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/admin/pages/Blogs.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Blogs_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/admin/pages/Blogs.vue?vue&type=template&id=908d9ad0&":
/*!***************************************************************************!*\
  !*** ./resources/js/admin/pages/Blogs.vue?vue&type=template&id=908d9ad0& ***!
  \***************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Blogs_vue_vue_type_template_id_908d9ad0___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./Blogs.vue?vue&type=template&id=908d9ad0& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/admin/pages/Blogs.vue?vue&type=template&id=908d9ad0&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Blogs_vue_vue_type_template_id_908d9ad0___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Blogs_vue_vue_type_template_id_908d9ad0___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);