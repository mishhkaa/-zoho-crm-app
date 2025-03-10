<template>
    <div>
      <h1>Zoho CRM Dashboard</h1>
  
      <!-- Create Account -->
      <h2>Create Account</h2>
      <form @submit.prevent="createAccount">
        <input v-model="accountName" placeholder="Account Name" required />
        <input v-model="website" placeholder="Website" />
        <input v-model="phone" placeholder="Phone" />
        <button type="submit">Create Account</button>
      </form>
  
      <!-- Accounts List -->
      <h2>Accounts</h2>
      <table>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Website</th>
          <th>Phone</th>
          <th>Actions</th>
        </tr>
        <tr v-for="account in accounts" :key="account.id">
          <td>{{ account.id }}</td>
          <td v-if="!editingAccount || editingAccount.id !== account.id">{{ account.Account_Name }}</td>
          <td v-if="!editingAccount || editingAccount.id !== account.id">{{ account.Website || 'N/A' }}</td>
          <td v-if="!editingAccount || editingAccount.id !== account.id">{{ account.Phone || 'N/A' }}</td>
  
          <!-- Editable Fields -->
          <td v-if="editingAccount && editingAccount.id === account.id">
            <input v-model="editingAccount.Account_Name" placeholder="New Name" />
            <input v-model="editingAccount.Website" placeholder="New Website" />
            <input v-model="editingAccount.Phone" placeholder="New Phone" />
          </td>
  
          <!-- Action Buttons -->
          <td>
            <button v-if="editingAccount && editingAccount.id === account.id" @click="updateAccount">Save</button>
            <button v-else @click="editAccount(account)">Edit</button>
            <button @click="deleteAccount(account.id)">Delete</button>
          </td>
        </tr>
      </table>
  
      <!-- Create Deal -->
      <h2>Create Deal</h2>
      <form @submit.prevent="createDeal">
        <input v-model="dealName" placeholder="Deal Name" required />
        <input v-model="stage" placeholder="Stage" required />
        <input v-model="accountId" placeholder="Account ID" required />
        <button type="submit">Create Deal</button>
      </form>
  
      <!-- Deals List -->
      <h2>Deals</h2>
      <table>
        <tr>
          <th>ID</th>
          <th>Deal Name</th>
          <th>Stage</th>
          <th>Actions</th>
        </tr>
        <tr v-for="deal in deals" :key="deal.id">
          <td>{{ deal.id }}</td>
          <td v-if="!editingDeal || editingDeal.id !== deal.id">{{ deal.Deal_Name }}</td>
          <td v-if="!editingDeal || editingDeal.id !== deal.id">{{ deal.Stage }}</td>
  
          <!-- Editable Fields -->
          <td v-if="editingDeal && editingDeal.id === deal.id">
            <input v-model="editingDeal.Deal_Name" placeholder="New Deal Name" />
            <input v-model="editingDeal.Stage" placeholder="New Stage" />
          </td>
  
          <!-- Action Buttons -->
          <td>
            <button v-if="editingDeal && editingDeal.id === deal.id" @click="updateDeal">Save</button>
            <button v-else @click="editDeal(deal)">Edit</button>
            <button @click="deleteDeal(deal.id)">Delete</button>
          </td>
        </tr>
      </table>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        accounts: [],
        deals: [],
        accountName: '',
        website: '',
        phone: '',
        dealName: '',
        stage: '',
        accountId: '',
        editingAccount: null,
        editingDeal: null
      };
    },
    methods: {
      fetchAccounts() {
        axios.get('/api/accounts')
          .then(response => {
            this.accounts = response.data.data;
          })
          .catch(error => console.error("Error fetching accounts:", error));
      },
      fetchDeals() {
        axios.get('/api/deals')
          .then(response => {
            this.deals = response.data.data;
          })
          .catch(error => console.error("Error fetching deals:", error));
      },
      createAccount() {
        axios.post('/api/account', {
          Account_Name: this.accountName,
          Website: this.website,
          Phone: this.phone
        }).then(() => {
          this.fetchAccounts();
          this.accountName = '';
          this.website = '';
          this.phone = '';
        }).catch(error => console.error("Error creating account:", error));
      },
      createDeal() {
        axios.post('/api/deal', {
          Deal_Name: this.dealName,
          Stage: this.stage,
          Account_Id: this.accountId
        }).then(() => {
          this.fetchDeals();
          this.dealName = '';
          this.stage = '';
          this.accountId = '';
        }).catch(error => console.error("Error creating deal:", error));
      },
      editAccount(account) {
        this.editingAccount = { ...account };
      },
      updateAccount() {
        axios.put(`/api/account/${this.editingAccount.id}`, {
          Account_Name: this.editingAccount.Account_Name,
          Website: this.editingAccount.Website,
          Phone: this.editingAccount.Phone
        }).then(() => {
          this.editingAccount = null;
          this.fetchAccounts();
        }).catch(error => console.error("Error updating account:", error));
      },
      deleteAccount(id) {
        if (!confirm("Are you sure you want to delete this account?")) return;
        axios.delete(`/api/account/${id}`)
          .then(() => this.fetchAccounts())
          .catch(error => console.error("Error deleting account:", error));
      },
      editDeal(deal) {
        this.editingDeal = { ...deal };
      },
      updateDeal() {
        axios.put(`/api/deal/${this.editingDeal.id}`, {
          Deal_Name: this.editingDeal.Deal_Name,
          Stage: this.editingDeal.Stage
        }).then(() => {
          this.editingDeal = null;
          this.fetchDeals();
        }).catch(error => console.error("Error updating deal:", error));
      },
      deleteDeal(id) {
        if (!confirm("Are you sure you want to delete this deal?")) return;
        axios.delete(`/api/deal/${id}`)
          .then(() => this.fetchDeals())
          .catch(error => console.error("Error deleting deal:", error));
      }
    },
    mounted() {
      this.fetchAccounts();
      this.fetchDeals();
    },
  };
  </script>
  
  <style>
  body {
    font-family: Arial, sans-serif;
    margin: 20px;
  }
  table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
  }
  th, td {
    border: 1px solid #ddd;
    padding: 10px;
    text-align: left;
  }
  th {
    background-color: #f4f4f4;
  }
  </style>
  