import { initializeApp } from
    "https://www.gstatic.com/firebasejs/10.14.1/firebase-app.js";

import { getAuth } from
    "https://www.gstatic.com/firebasejs/10.14.1/firebase-auth.js";

import { getFirestore } from
    "https://www.gstatic.com/firebasejs/10.14.1/firebase-firestore.js";

const firebaseConfig = {
    apiKey: "AIzaSyDafFXHG_bEOtUEpfL1Q_8i_ySf-X2VAbI",
    authDomain: "tiendavirtual-c9e54.firebaseapp.com",
    projectId: "tiendavirtual-c9e54",
    storageBucket: "tiendavirtual-c9e54.firebasestorage.app",
    messagingSenderId: "457497428718",
    appId: "1:457497428718:web:8b6d55856d2688b01129ff",
};

const app = initializeApp(firebaseConfig);

export const auth = getAuth(app);
export const db = getFirestore(app);