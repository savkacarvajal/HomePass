package com.example.test

import android.annotation.SuppressLint
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.TextView
import androidx.recyclerview.widget.RecyclerView
import java.util.Locale

class UserAdapter(
    private var users: MutableList<User>,
    private val onUserClickListener: (User) -> Unit
) : RecyclerView.Adapter<UserAdapter.UserViewHolder>() {

    private var originalUsers: MutableList<User> = mutableListOf()

    init {
        this.originalUsers.addAll(users)
    }

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): UserViewHolder {
        val view = LayoutInflater.from(parent.context).inflate(R.layout.list_item_user, parent, false)
        return UserViewHolder(view)
    }

    override fun onBindViewHolder(holder: UserViewHolder, position: Int) {
        val user = users[position]
        holder.bind(user)
        holder.itemView.setOnClickListener { onUserClickListener(user) }
    }

    override fun getItemCount(): Int = users.size

    @SuppressLint("NotifyDataSetChanged")
    fun filter(query: String) {
        users.clear()
        if (query.isBlank()) {
            users.addAll(originalUsers)
        } else {
            val lowerCaseQuery = query.lowercase(Locale.getDefault())
            for (user in originalUsers) {
                if (user.nombres.lowercase(Locale.getDefault()).contains(lowerCaseQuery) ||
                    user.apellidos.lowercase(Locale.getDefault()).contains(lowerCaseQuery)) {
                    users.add(user)
                }
            }
        }
        notifyDataSetChanged()
    }

    @SuppressLint("NotifyDataSetChanged")
    fun updateUsers(newUsers: List<User>) {
        originalUsers.clear()
        originalUsers.addAll(newUsers)
        users.clear()
        users.addAll(newUsers)
        notifyDataSetChanged()
    }

    class UserViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {
        private val nombreCompletoTextView: TextView = itemView.findViewById(R.id.textViewNombreCompleto)
        private val emailTextView: TextView = itemView.findViewById(R.id.textViewEmail)

        fun bind(user: User) {
            nombreCompletoTextView.text = "${user.nombres} ${user.apellidos}"
            emailTextView.text = user.email
        }
    }
}