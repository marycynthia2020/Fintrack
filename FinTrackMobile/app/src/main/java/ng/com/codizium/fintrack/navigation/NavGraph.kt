package ng.com.codizium.fintrack.navigation

import androidx.compose.foundation.layout.padding
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.filled.ArrowDownward
import androidx.compose.material.icons.filled.ArrowUpward
import androidx.compose.material.icons.filled.Home
import androidx.compose.material.icons.filled.Person
import androidx.compose.material.icons.filled.Settings
import androidx.compose.material3.Icon
import androidx.compose.material3.NavigationBar
import androidx.compose.material3.NavigationBarItem
import androidx.compose.material3.NavigationBarItemDefaults
import androidx.compose.material3.Scaffold
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.runtime.getValue
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.graphics.vector.ImageVector
import androidx.compose.ui.unit.sp
import androidx.navigation.NavController
import androidx.navigation.NavGraph.Companion.findStartDestination
import androidx.navigation.compose.NavHost
import androidx.navigation.compose.composable
import androidx.navigation.compose.currentBackStackEntryAsState
import androidx.navigation.compose.rememberNavController
import ng.com.codizium.fintrack.ui.screens.auth.CreateOrganizationScreen
import ng.com.codizium.fintrack.ui.screens.auth.LoginScreen
import ng.com.codizium.fintrack.ui.screens.auth.OnboardingScreen
import ng.com.codizium.fintrack.ui.screens.dashboard.DashboardScreen
import ng.com.codizium.fintrack.ui.screens.expense.AddExpenseScreen
import ng.com.codizium.fintrack.ui.screens.expense.ExpenseScreen
import ng.com.codizium.fintrack.ui.screens.income.AddIncomeScreen
import ng.com.codizium.fintrack.ui.screens.income.IncomeScreen
import ng.com.codizium.fintrack.ui.screens.members.InviteMemberScreen
import ng.com.codizium.fintrack.ui.screens.members.MembersScreen
import ng.com.codizium.fintrack.ui.screens.settings.SettingsScreen
import ng.com.codizium.fintrack.ui.screens.transactions.TransactionDetailScreen
import ng.com.codizium.fintrack.ui.theme.FinTrackBlue
import ng.com.codizium.fintrack.ui.theme.TextSecondary

object Routes {
    const val LOGIN = "login"
    const val ONBOARDING = "onboarding"
    const val CREATE_ORG = "create_org"
    const val DASHBOARD = "dashboard"
    const val INCOME = "income"
    const val ADD_INCOME = "add_income"
    const val EXPENSES = "expenses"
    const val ADD_EXPENSE = "add_expense"
    const val TRANSACTION_DETAIL = "transaction_detail/{transactionId}"
    const val MEMBERS = "members"
    const val INVITE_MEMBER = "invite_member"
    const val SETTINGS = "settings"

    fun transactionDetail(id: String) = "transaction_detail/$id"
}

private data class BottomNavItem(val route: String, val icon: ImageVector, val label: String)

private val bottomNavItems = listOf(
    BottomNavItem(Routes.DASHBOARD, Icons.Filled.Home, "Dashboard"),
    BottomNavItem(Routes.INCOME, Icons.Filled.ArrowUpward, "Income"),
    BottomNavItem(Routes.EXPENSES, Icons.Filled.ArrowDownward, "Expenses"),
    BottomNavItem(Routes.MEMBERS, Icons.Filled.Person, "Members"),
    BottomNavItem(Routes.SETTINGS, Icons.Filled.Settings, "Settings"),
)

private val bottomNavRoutes = bottomNavItems.map { it.route }.toSet()

@Composable
fun AppNavGraph() {
    val navController = rememberNavController()
    val navBackStackEntry by navController.currentBackStackEntryAsState()
    val currentRoute = navBackStackEntry?.destination?.route

    val showBottomBar = currentRoute in bottomNavRoutes

    Scaffold(
        bottomBar = {
            if (showBottomBar) {
                FinTrackBottomBar(navController = navController, currentRoute = currentRoute)
            }
        }
    ) { innerPadding ->
        NavHost(
            navController = navController,
            startDestination = Routes.LOGIN,
            modifier = Modifier.padding(innerPadding)
        ) {
            composable(Routes.LOGIN) { LoginScreen(navController) }
            composable(Routes.ONBOARDING) { OnboardingScreen(navController) }
            composable(Routes.CREATE_ORG) { CreateOrganizationScreen(navController) }
            composable(Routes.DASHBOARD) { DashboardScreen(navController) }
            composable(Routes.INCOME) { IncomeScreen(navController) }
            composable(Routes.ADD_INCOME) { AddIncomeScreen(navController) }
            composable(Routes.EXPENSES) { ExpenseScreen(navController) }
            composable(Routes.ADD_EXPENSE) { AddExpenseScreen(navController) }
            composable(Routes.TRANSACTION_DETAIL) { backStack ->
                val id = backStack.arguments?.getString("transactionId") ?: ""
                TransactionDetailScreen(navController, id)
            }
            composable(Routes.MEMBERS) { MembersScreen(navController) }
            composable(Routes.INVITE_MEMBER) { InviteMemberScreen(navController) }
            composable(Routes.SETTINGS) { SettingsScreen(navController) }
        }
    }
}

@Composable
private fun FinTrackBottomBar(navController: NavController, currentRoute: String?) {
    NavigationBar(containerColor = Color.White) {
        bottomNavItems.forEach { item ->
            NavigationBarItem(
                selected = currentRoute == item.route,
                onClick = {
                    navController.navigate(item.route) {
                        popUpTo(navController.graph.findStartDestination().id) { saveState = true }
                        launchSingleTop = true
                        restoreState = true
                    }
                },
                icon = { Icon(item.icon, contentDescription = item.label) },
                label = { Text(item.label, fontSize = 10.sp) },
                colors = NavigationBarItemDefaults.colors(
                    selectedIconColor = FinTrackBlue,
                    selectedTextColor = FinTrackBlue,
                    unselectedIconColor = TextSecondary,
                    unselectedTextColor = TextSecondary,
                    indicatorColor = FinTrackBlue.copy(alpha = 0.12f)
                )
            )
        }
    }
}
