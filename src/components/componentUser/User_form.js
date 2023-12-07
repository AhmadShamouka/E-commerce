import React, { useState, useEffect } from "react";
import axios from "axios";
import { useParams, useLocation, useNavigate } from "react-router-dom";
import "../index.css";

function User_form() {
  const { id } = useParams();
  const location = useLocation();
  const Authorization = "Bearer " + location.state?.data;
  const navigate = useNavigate();
  const [inputs, setInputs] = useState({});
  const [formData, setFormData] = useState({
    headers: {
      Authorization: Authorization,
    },
    id: id,
  });
  const getUser = async () => {
    try {
      const response = await axios.post(
        `http://localhost/e-commerce/backend/api/getSingleUser.php/${id}`,
        formData,
        {
          headers: {
            Authorization: Authorization,
          },
        }
      );
      console.log(response.data);
      setInputs(response.data);
    } catch (error) {
      console.error("Error getting user:", error);
    }
  };

  useEffect(() => {
    getUser();
  }, []);
  const goTo = () => {};

  const [editData, setEditData] = useState({
    headers: {
      Authorization: Authorization,
    },
    id: id,
    username: "",
    role_name: "",
  });

  const handleChange = (event) => {
    const name = event.target.name;
    const value = event.target.value;
    setEditData((prevInputs) => ({ ...prevInputs, [name]: value }));
  };

  const handleSubmit = async (event) => {
    event.preventDefault();
    try {
      const response = await axios.put(
        `http://localhost/e-commerce/backend/api/editUser.php/${id}`,
        editData,
        {
          headers: {
            Authorization: Authorization,
          },
        }
      );
      console.log(response.data);
      setFormData(response.data);
    } catch (error) {
      console.error("Error during form submission:", error);
    }
  };

  return (
    <div>
      <h1>Edit User</h1>
      <form onSubmit={handleSubmit}>
        <div>
          <label>Username:</label>
          <input
            name="username"
            value={editData.username}
            onChange={handleChange}
            required
          />
        </div>
        <div>
          <label>Role Name:</label>
          <input
            name="role_name"
            value={editData.role_name}
            onChange={handleChange}
            required
          />
        </div>
        <div>
          <button type="submit">Edit</button>
        </div>
      </form>
      <div>
        <button type="submit" onClick={goTo}>
          Edit
        </button>
      </div>
    </div>
  );
}

export default User_form;
