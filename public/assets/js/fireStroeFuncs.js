// Your web app's Firebase configuration
//usefor  application disk top An mobile;

var firebaseConfig = {
    apiKey: "AIzaSyDAqGsiuagxEpu29QEFia9j_eSUlDnfZWE",
    authDomain: "funnyshopping-4d1d7.firebaseapp.com",
    databaseURL: "https://funnyshopping-4d1d7.firebaseio.com",
    projectId: "funnyshopping-4d1d7",
    storageBucket: "funnyshopping-4d1d7.appspot.com",
    messagingSenderId: "602411626214",
    appId: "1:602411626214:web:971bf060e6c91cccf99527"
};
// Initialize Firebase
// Sign in with email and pass.

firebase.initializeApp(firebaseConfig);


setTimeout(() => {
//  signIn();
//  signOut();
//   createUser();

}, 4000);

function  signIn(){     
 
toast("Entering....")

firebase.auth().signInWithEmailAndPassword("a@a.com", "123123").catch(function(error) {
console.log(firebase.auth())
// Handle Errors here.
    var errorCode = error.code;
    var errorMessage = error.message;
    if (errorCode === 'auth/wrong-password') {
        alert('Wrong password.');
    } else {
        alert(errorMessage);
    }
    console.log(error);
   
});

}   


function signOut(){

toast("Outing...")

firebase.auth().signOut().then(() => {
   toast("Signing out");

}).catch((error) => {
    toast("Error Error");
});
}


function createUser(){
firebase.auth().createUserWithEmailAndPassword("c@c.com", "789789").catch(function(error) {
        // Handle Errors here.
        var errorCode = error.code;
        var errorMessage = error.message;
        if (errorCode == 'auth/weak-password') {
        alert('The password is too weak.');
        } else {
        alert(errorMessage);
        }
        console.log(error);
    });
}


function getUser(){
console.log(firebase.auth().currentUser.email);
}

function getUID(){
console.log(firebase.auth().user.uid);
}

function getMyData(){
const dbRef  = firebase.firestore().collection('products');
//  const usersRef = dbRef.child("products");
dbRef.get().then((snapshot) => {
        const data = snapshot.docs.map((doc) => ({
        id: doc.id,
        ...doc.data(),
        }));
        console.log("products:", data); 
        // [ { id: 'glMeZvPpTN1Ah31sKcnj', title: 'The Great Gatsby' } ]
    });
}

function addData(namep , pricep){

firebase.firestore()
    .collection("products")
    .add({
        name: namep,
        price:pricep
    })
    .then((ref) => {
        console.log("Added doc with ID: ", ref.id);
        // Added doc with ID:  ZzhIgLqELaoE3eSsOazu
    });
}

function updateData(id , ob){

var ref=firebase.firestore().collection("products").doc(id);

    ref.update(ob).then(() => {
        console.log("Document updated"); // Document updated
    })
    .catch((error) => {
        console.error("Error updating doc", error);
    });	
}

function deleteData(id){
firebase
.firestore()
.collection("products")
.doc(id)
.delete()
.then(() => console.log("Document deleted")) // Document deleted
.catch((error) => console.error("Error deleting document", error));

}

function addBach(){
let db = firebase.firestore().collection("products")

/// Batch Thing //
var batch = firebase.firestore().batch();

let cars = [{name: 'name2', price: 'price2'}, {name: 'name3', price: 'price3'}]

cars.forEach(c => {
    var newCityRef = db.doc();
//  let ref = colRef.doc(`${c.name}`)
    batch.set(newCityRef, {
        name: `${c.name}`,
        model: `${c.price}`
    })
})

return batch.commit()
.then(data => {
    console.log('good')
})
.catch(error => {
    console.log('there is an error')
})
}


   