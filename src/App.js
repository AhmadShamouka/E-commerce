import { BrowserRouter as Router, Routes, Route } from "react-router-dom";
import "./App.css";
import React from "react";
import UserSignin from "./components/componentUser/UserSignin";
import UserSignUp from "./components/componentUser/UserSignUp";
import User_form from "./components/componentUser/User_form";
import UserOrder from "./components/componentUser/UserOrder";
import CreateProduct from "./components/componentProduct/CreateProduct";
import Products from "./components/componentProduct/Products";

function App() {
  return (
    <div className="App">
      <React.Fragment>
        <Router>
          <Routes>
            <Route path="/get" element={<Products />} />
            <Route path="/:id/create" element={<CreateProduct />} />
            <Route path="/:id/orders" element={<UserOrder />} />
            <Route path="/" element={<UserSignin />} />
            <Route path="/signup" element={<UserSignUp />} />
            <Route path="/:id/userform" element={<User_form />} />
          </Routes>
        </Router>
      </React.Fragment>
    </div>
  );
}

export default App;
