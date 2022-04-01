import localforage from "localforage";
// import localforageGetItems from 'localforage-getitems';
// https://netterminalmachine.com/blog/2018/persisting-a-vuex-store-to-indexed-db

export const localForageService = localforage.createInstance({
  name: "mydatabase",
  version: 1.0,
  storeName: "mystorageobj"
});

localForageService
  .setDriver([
    localforage.INDEXEDDB,
    localforage.WEBSQL,
    localforage.LOCALSTORAGE
  ])
  .then
  //   () => {
  //   // use this to test your db connection - delete later
  //   localForageService.setItem("testkey", "testvalue", function() {
  //     console.log(
  //       "Of the driver options given, localforage is using:" +
  //         localForageService.driver()
  //     );
  //   });
  // }
  ()
  .catch(error => {
    console.log("Error setting up storage cache: " + error);
  });

export default {
  localforage,
  localForageService
};
