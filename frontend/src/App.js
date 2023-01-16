import React, { useState } from 'react';
import axios from 'axios';
import { Form, Input, Button, notification } from 'antd';
import CountryPhoneInput, { ConfigProvider } from 'antd-country-phone-input';
import en from 'world_countries_lists/data/countries/en/world.json';

const LeadForm = () => {
  const [formData, setFormData] = useState({
    fullName: '',
    email: '',
    phone: ''
  });
  const [isLoading, setIsLoading] = useState(false);
  const [api, contextHolder] = notification.useNotification();

  const handleInputChange = event => {
    setFormData({
      ...formData,
      [event.target.name]: event.target.value
    });
  };


  const openNotificationWithIcon = (type, message, description) => {
    api[type]({
      message: message,
      description: description
    });
  };

  const handleSubmit = async () => {
    setIsLoading(true);

    //validate form data here
    await axios.post('http://localhost:8888/phpserver/', {
      fullName: formData.fullName,
      email: formData.email,
      phone: formData.phoneInput
    })
      .then(res => {
        //handle success response here
        if(res?.data?.status !== "error") {
          openNotificationWithIcon('success', 'Success', res?.data?.message);
        } else {
          openNotificationWithIcon('error', 'Error', res?.data?.message);
        }
        
      })
      .catch(err => {
        console.log(err)
        openNotificationWithIcon('error', 'Success', err?.message);
        //handle error here
      })
      .finally(() => {
        setIsLoading(false);
      });
  };

  const handlePhoneInput = ({ phone, code }) => {
    const value = `${code}${phone}`;

    setFormData({
      ...formData,
      phoneInput: value,
      phone: phone
    });
  }

  return (
    <div className="form-container">
      {contextHolder}
      <Form className="form-center" disabled={isLoading}>
        <Form.Item>
          <Input
            name="fullName"
            placeholder="Full Name"
            onChange={handleInputChange}
            value={formData.fullName}
            required
          />
        </Form.Item>
        <Form.Item>
          <Input
            name="email"
            type="email"
            placeholder="Email"
            onChange={handleInputChange}
            value={formData.email}
            required
          />
        </Form.Item>
        <Form.Item>
          <ConfigProvider locale={en}>
            <CountryPhoneInput
              placeholder="Phone Number"
              onChange={handlePhoneInput}
              value={{
                phone: formData.phone,
                short: "TR"
              }}
              required
            />
          </ConfigProvider>
        </Form.Item>
        <Form.Item>
          <Button loading={isLoading} type="primary" htmlType="button" onClick={() => handleSubmit()}>
            Submit
          </Button>
        </Form.Item>
      </Form>
    </div>
  );
};

export default LeadForm;
