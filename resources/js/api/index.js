import axios from "axios"

const Api = axios.create({
  baseURL: 'http://localhost:8000',

  // baseURL: 'https://yudhisparepart.my.id',
})

export default Api
