import React, { useState } from "react";
import axios from "axios";
import { useNavigate } from "react-router-dom";
import "../index.css";
const UserSignin = () => {
  const navigate = useNavigate();
  const [formData, setFormData] = useState({
    username: "",
    password: "",
  });
  const handleChange = (e) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    console.log(formData);
    try {
      const response = await axios.post(
        "http://localhost/e-commerce/backend/api/signin.php",
        formData
      );

      console.log(response.data);
      const id = response.data.id;
      const header = response.data.jwt;
      if (response.data.jwt) {
        navigate(`/${id}/create`, { state: { data: header } });
      }
    } catch (error) {
      console.error("Error during form submission:", error);
    }
  };
  return (
    <div>
      <h2>Sign In</h2>

      <form onSubmit={handleSubmit}>
        <div>
          <label>Username:</label>
          <input type="text" name="username" onChange={handleChange} required />
        </div>
        <div>
          <label>Password:</label>
          <input
            type="password"
            name="password"
            onChange={handleChange}
            required
          />
        </div>
        <div>
          <button>Sign In</button>
        </div>
      </form>
    </div>
  );
};

export default UserSignin;
