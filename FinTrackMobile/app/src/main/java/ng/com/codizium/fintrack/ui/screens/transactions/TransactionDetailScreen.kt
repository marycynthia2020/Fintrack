package ng.com.codizium.fintrack.ui.screens.transactions

import androidx.compose.foundation.background
import androidx.compose.foundation.layout.*
import androidx.compose.foundation.rememberScrollState
import androidx.compose.foundation.shape.CircleShape
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.foundation.verticalScroll
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.filled.ArrowBack
import androidx.compose.material.icons.filled.Delete
import androidx.compose.material.icons.filled.Edit
import androidx.compose.material.icons.filled.ArrowDownward
import androidx.compose.material.icons.filled.ArrowUpward
import androidx.compose.material3.*
import androidx.compose.runtime.*
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.draw.clip
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import androidx.navigation.NavController
import ng.com.codizium.fintrack.data.model.*
import ng.com.codizium.fintrack.ui.theme.*

@Composable
fun TransactionDetailScreen(navController: NavController, transactionId: String) {
    val tx = MockData.getById(transactionId) ?: MockData.transactions.first()
    val isIncome = tx.type == TransactionType.INCOME

    Column(
        modifier = Modifier
            .fillMaxSize()
            .background(BackgroundGray)
    ) {
        // Top bar
        Row(
            modifier = Modifier
                .fillMaxWidth()
                .background(CardWhite)
                .padding(horizontal = 4.dp, vertical = 8.dp),
            verticalAlignment = Alignment.CenterVertically
        ) {
            IconButton(onClick = { navController.popBackStack() }) {
                Icon(Icons.Filled.ArrowBack, null, tint = TextPrimary)
            }
            Text(
                "Transaction Details",
                fontSize = 16.sp,
                fontWeight = FontWeight.SemiBold,
                color = TextPrimary,
                modifier = Modifier.weight(1f)
            )
            OutlinedButton(
                onClick = {},
                shape = RoundedCornerShape(8.dp),
                contentPadding = PaddingValues(horizontal = 10.dp, vertical = 6.dp),
                border = ButtonDefaults.outlinedButtonBorder(enabled = true)
            ) {
                Icon(Icons.Filled.Edit, null, modifier = Modifier.size(14.dp), tint = FinTrackBlue)
                Spacer(Modifier.width(4.dp))
                Text("Edit", fontSize = 12.sp, color = FinTrackBlue)
            }
            Spacer(Modifier.width(8.dp))
            Button(
                onClick = { navController.popBackStack() },
                shape = RoundedCornerShape(8.dp),
                contentPadding = PaddingValues(horizontal = 10.dp, vertical = 6.dp),
                colors = ButtonDefaults.buttonColors(containerColor = ExpenseRed)
            ) {
                Icon(Icons.Filled.Delete, null, modifier = Modifier.size(14.dp))
                Spacer(Modifier.width(4.dp))
                Text("Delete", fontSize = 12.sp)
            }
            Spacer(Modifier.width(4.dp))
        }

        Column(
            modifier = Modifier
                .fillMaxSize()
                .verticalScroll(rememberScrollState())
                .padding(16.dp),
            horizontalAlignment = Alignment.CenterHorizontally
        ) {
            Spacer(Modifier.height(16.dp))

            // Type badge
            Box(
                modifier = Modifier
                    .clip(RoundedCornerShape(6.dp))
                    .background(if (isIncome) IncomeGreenSurface else ExpenseRedSurface)
                    .padding(horizontal = 12.dp, vertical = 4.dp)
            ) {
                Text(
                    if (isIncome) "Income" else "Expense",
                    fontSize = 12.sp,
                    color = if (isIncome) IncomeGreen else ExpenseRed,
                    fontWeight = FontWeight.Medium
                )
            }

            Spacer(Modifier.height(12.dp))

            // Icon circle
            Box(
                modifier = Modifier
                    .size(64.dp)
                    .clip(CircleShape)
                    .background(if (isIncome) IncomeGreenSurface else ExpenseRedSurface),
                contentAlignment = Alignment.Center
            ) {
                Icon(
                    if (isIncome) Icons.Filled.ArrowUpward else Icons.Filled.ArrowDownward,
                    null,
                    tint = if (isIncome) IncomeGreen else ExpenseRed,
                    modifier = Modifier.size(32.dp)
                )
            }

            Spacer(Modifier.height(12.dp))

            Text(
                formatAmount(tx.amount),
                fontSize = 28.sp,
                fontWeight = FontWeight.Bold,
                color = TextPrimary
            )
            Text(tx.description, fontSize = 14.sp, color = TextSecondary)

            Spacer(Modifier.height(24.dp))

            Card(
                modifier = Modifier.fillMaxWidth(),
                shape = RoundedCornerShape(12.dp),
                colors = CardDefaults.cardColors(containerColor = CardWhite),
                elevation = CardDefaults.cardElevation(2.dp)
            ) {
                Column(modifier = Modifier.padding(20.dp)) {
                    DetailRow("Description", tx.description)
                    HorizontalDivider(color = BorderGray, modifier = Modifier.padding(vertical = 12.dp))
                    DetailRow("Date", tx.date)
                    HorizontalDivider(color = BorderGray, modifier = Modifier.padding(vertical = 12.dp))
                    DetailRow("Created By", tx.createdBy)
                    HorizontalDivider(color = BorderGray, modifier = Modifier.padding(vertical = 12.dp))
                    DetailRow("Created At", tx.createdAt.ifEmpty { tx.date + " 10:30 AM" })
                    HorizontalDivider(color = BorderGray, modifier = Modifier.padding(vertical = 12.dp))
                    DetailRow("Last Updated By", tx.updatedBy.ifEmpty { tx.createdBy })
                    HorizontalDivider(color = BorderGray, modifier = Modifier.padding(vertical = 12.dp))
                    DetailRow("Last Updated At", tx.updatedAt.ifEmpty { tx.date + " 10:30 AM" })
                }
            }
        }
    }
}

@Composable
private fun DetailRow(label: String, value: String) {
    Row(
        modifier = Modifier.fillMaxWidth(),
        horizontalArrangement = Arrangement.SpaceBetween
    ) {
        Text(label, fontSize = 13.sp, color = TextSecondary, modifier = Modifier.weight(1f))
        Text(value, fontSize = 13.sp, color = TextPrimary, fontWeight = FontWeight.Medium, modifier = Modifier.weight(1.5f))
    }
}
