// Create_admin.jsx

import React, { useState } from "react";
import "../index.css";
import axios from "axios";
import { useNavigate } from "react-router-dom";

function UserSignUp() {
  const navigate = useNavigate();
  const [formData, setFormData] = useState({
    username: "",
    password: "",
    role_name: "Admin",
  });

  const handleChange = (e) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  const handleSubmit = async (event) => {
    event.preventDefault();

    try {
      const response = await axios.post(
        "http://localhost/e-commerce/backend/api/signup.php",
        formData
      );
      console.log(response.data);
      if (response.data.status === "success") {
        navigate("/");
      }
    } catch (error) {
      console.error("Error during form submission:", error);
    }
  };

  return (
    <div className="signin-container">
      <h1>Registration Form</h1>
      <form onSubmit={handleSubmit}>
        <div className="form-group">
          <label htmlFor="username">Username:</label>
          <input
            type="username"
            name="username"
            onChange={handleChange}
            required
          />
        </div>

        <div className="form-group">
          <label htmlFor="password">Password:</label>
          <input
            type="password"
            name="password"
            onChange={handleChange}
            required
          />
        </div>
        <div className="form-group">
          <label htmlFor="role_name">role_name:</label>
          <select name="role_name" defaultValue="Admin" onChange={handleChange}>
            <option value="Admin">Admin</option>
            <option value="User">User</option>
            <option value="Seller">Seller</option>
          </select>
        </div>

        <div className="form-group">
          <button type="submit">Submit</button>
        </div>
      </form>
    </div>
  );
}

export default UserSignUp;
